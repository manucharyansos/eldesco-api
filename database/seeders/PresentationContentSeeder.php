<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\News;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Loads the website content derived from the ELDESCO company presentation
 * (database/seeders/data/presentation.json).
 *
 * Safe to run repeatedly. It synchronises every public database-backed
 * content area with the presentation defaults: settings, navigation, pages,
 * services, projects, team, news and gallery. Run it after deploying, then
 * continue editing the content from the admin panel:
 *
 *   php artisan db:seed --class=PresentationContentSeeder --force
 *
 * Running it again intentionally restores the presentation defaults for the
 * records managed by this seeder; unrelated records are left untouched.
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
            $this->team($data['team']);
            $this->news($data['news']);
            $this->gallery($data['services'], $data['pages']);
            $this->pages($data['pages']);
        });
    }

    private function settings(array $settings): void
    {
        foreach ($settings as $setting) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'group' => $setting['group'],
                    'value' => $this->json($setting['value']),
                    'type' => $setting['type'] ?? 'text',
                    'is_public' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
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
                ?? new Service;

            $model->fill($service)->save();
        }
    }

    private function projects(array $projects): void
    {
        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['title_en' => $project['title_en']],
                $project
            );
        }
    }

    private function team(array $members): void
    {
        foreach ($members as $member) {
            TeamMember::updateOrCreate(
                ['name_hy' => $member['name_hy']],
                $member
            );
        }
    }

    private function news(array $items): void
    {
        foreach ($items as $item) {
            News::updateOrCreate(
                ['slug_en' => $item['slug_en']],
                $item
            );
        }
    }

    /**
     * The public CMS galleries and the legacy /api/gallery endpoint use the
     * same presentation photos. Build the gallery table from the five service
     * page galleries so the image list has one source of truth.
     */
    private function gallery(array $services, array $pages): void
    {
        $serviceSlugs = array_column($services, 'slug');
        $serviceTitles = [];
        foreach ($services as $service) {
            $serviceTitles[$service['slug']] = [
                'hy' => $service['title_hy'],
                'en' => $service['title_en'],
                'ru' => $service['title_ru'],
            ];
        }

        $rows = [];
        $order = 1;

        foreach ($pages as $page) {
            if (! in_array($page['slug'], $serviceSlugs, true)) {
                continue;
            }

            foreach ($page['sections'] as $section) {
                if (($section['type'] ?? null) !== 'gallery') {
                    continue;
                }

                foreach ($section['content']['images'] ?? [] as $image) {
                    $path = $image['image'] ?? null;
                    if (! is_string($path) || $path === '' || isset($rows[$path])) {
                        continue;
                    }

                    $caption = $image['caption'] ?? $image['alt'] ?? [];
                    $fallback = $serviceTitles[$page['slug']];
                    $rows[$path] = [
                        'title_hy' => $caption['hy'] ?? $fallback['hy'],
                        'title_en' => $caption['en'] ?? $fallback['en'],
                        'title_ru' => $caption['ru'] ?? $fallback['ru'],
                        'image_url' => $path,
                        'thumbnail_url' => $path,
                        'category' => $page['slug'],
                        'order_index' => $order++,
                    ];
                }
            }
        }

        Gallery::query()
            ->where('image_url', 'like', '/images/deck/%')
            ->whereNotIn('image_url', array_keys($rows))
            ->delete();

        foreach ($rows as $path => $row) {
            Gallery::updateOrCreate(['image_url' => $path], $row);
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
