<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Loads the website content derived from the ELDESCO company presentation
 * (database/seeders/data/presentation.json).
 *
 * Safe to run on a fresh database. On a live database it RESETS the pages,
 * services and menus it manages to the presentation defaults, so run it
 * once after deploying and edit everything else from the admin panel:
 *
 *   php artisan db:seed --class=PresentationContentSeeder --force
 *
 * Site settings are only created when missing - existing values are kept.
 */
class PresentationContentSeeder extends Seeder
{
    public function run(): void
    {
        $data = json_decode(
            file_get_contents(database_path('seeders/data/presentation.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        DB::transaction(function () use ($data) {
            $this->settings($data['settings']);
            $this->navigation($data['navigation']);
            $this->services($data['services']);
            $this->projects($data['projects']);
            $this->pages($data['pages']);
        });
    }

    private function settings(array $settings): void
    {
        foreach ($settings as $setting) {
            if (DB::table('site_settings')->where('key', $setting['key'])->exists()) {
                continue;
            }

            DB::table('site_settings')->insert([
                'group' => $setting['group'],
                'key' => $setting['key'],
                'value' => $this->json($setting['value']),
                'type' => $setting['type'] ?? 'text',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function navigation(array $menus): void
    {
        foreach ($menus as $menu => $items) {
            DB::table('navigation_items')->where('menu', $menu)->delete();

            foreach ($items as $index => $item) {
                $parentId = DB::table('navigation_items')->insertGetId($this->navRow($menu, $item, $index, null));

                foreach ($item['children'] ?? [] as $childIndex => $child) {
                    DB::table('navigation_items')->insert($this->navRow($menu, $child, $childIndex, $parentId));
                }
            }
        }
    }

    private function navRow(string $menu, array $item, int $order, ?int $parentId): array
    {
        return [
            'menu' => $menu,
            'parent_id' => $parentId,
            'label' => $this->json($item['label']),
            'url' => $item['url'] ?? null,
            'page_slug' => $item['page_slug'] ?? null,
            'target' => $item['target'] ?? '_self',
            'is_enabled' => $item['is_enabled'] ?? true,
            'sort_order' => $order,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function services(array $services): void
    {
        foreach ($services as $service) {
            $model = Service::where('slug', $service['slug'])->first()
                ?? Service::whereNull('slug')->where('order_index', $service['order_index'])->first()
                ?? new Service();

            $model->fill($service)->save();
        }
    }

    private function projects(array $projects): void
    {
        foreach ($projects as $project) {
            $model = Project::where('title_en', $project['title_en'])->first();

            if (! $model) {
                Project::create($project);
                continue;
            }

            if (! $model->image_url) {
                $model->image_url = $project['image_url'];
                $model->save();
            }
        }
    }

    private function pages(array $pages): void
    {
        foreach ($pages as $definition) {
            $sections = $definition['sections'];
            unset($definition['sections']);

            $page = Page::updateOrCreate(
                ['slug' => $definition['slug']],
                $definition + ['is_published' => true]
            );

            $page->sections()->delete();

            foreach ($sections as $index => $section) {
                $page->sections()->create([
                    'key' => $section['key'],
                    'type' => $section['type'],
                    'content' => $section['content'],
                    'settings' => $section['settings'] ?? [],
                    'is_enabled' => $section['is_enabled'] ?? true,
                    'sort_order' => $index,
                ]);
            }
        }
    }

    private function json(mixed $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }
}
