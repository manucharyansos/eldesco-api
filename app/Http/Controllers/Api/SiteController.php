<?php

namespace App\Http\Controllers\Api;

use App\Support\Localizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Site-wide content that is not tied to a single page: company details,
 * contact info, footer texts, interface labels and the navigation menus.
 */
class SiteController extends Controller
{
    private const MENUS = ['header', 'footer'];

    /** Public payload used by the web client on every page render. */
    public function show(Request $request): JsonResponse
    {
        $lang = Localizer::language($request->query('lang'));

        $settings = [];
        foreach (DB::table('site_settings')->where('is_public', true)->get() as $row) {
            $settings[$row->key] = Localizer::localize($this->decode($row->value), $lang);
        }

        $navigation = [];
        foreach (self::MENUS as $menu) {
            $navigation[$menu] = $this->tree($menu, false, $lang);
        }

        return response()->json([
            'settings' => $settings,
            'navigation' => $navigation,
        ]);
    }

    /** Raw (all languages) payload for the admin editor. */
    public function adminShow(): JsonResponse
    {
        $settings = [];
        foreach (DB::table('site_settings')->orderBy('group')->orderBy('key')->get() as $row) {
            $settings[$row->key] = [
                'group' => $row->group,
                'type' => $row->type,
                'value' => $this->decode($row->value),
            ];
        }

        $navigation = [];
        foreach (self::MENUS as $menu) {
            $navigation[$menu] = $this->tree($menu, true, null);
        }

        return response()->json([
            'settings' => $settings,
            'navigation' => $navigation,
        ]);
    }

    /** Upsert a map of {key: value}. Values may be strings, numbers or {hy,en,ru} objects. */
    public function updateSettings(Request $request): JsonResponse
    {
        $data = $request->validate([
            'settings' => 'required|array',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['settings'] as $key => $value) {
                $key = (string) $key;
                if (! preg_match('/^[a-z0-9_.-]{1,120}$/', $key)) {
                    continue;
                }

                $existing = DB::table('site_settings')->where('key', $key)->first();
                $payload = [
                    'value' => $this->encode($value),
                    'updated_at' => now(),
                ];

                if ($existing) {
                    DB::table('site_settings')->where('id', $existing->id)->update($payload);
                } else {
                    DB::table('site_settings')->insert($payload + [
                        'group' => explode('.', $key)[0],
                        'key' => $key,
                        'type' => is_array($value) ? 'localized' : 'text',
                        'is_public' => true,
                        'created_at' => now(),
                    ]);
                }
            }
        });

        return $this->adminShow();
    }

    /** Replace the given menus. Each item: label{hy,en,ru}, page_slug|url, target, is_enabled, children[]. */
    public function updateNavigation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'menus' => 'required|array',
            'menus.*' => 'array',
            'menus.*.*.label' => 'required|array',
            'menus.*.*.page_slug' => 'nullable|string|max:255',
            'menus.*.*.url' => 'nullable|string|max:500',
            'menus.*.*.target' => 'nullable|in:_self,_blank',
            'menus.*.*.is_enabled' => 'nullable|boolean',
            'menus.*.*.children' => 'nullable|array',
            'menus.*.*.children.*.label' => 'required|array',
            'menus.*.*.children.*.page_slug' => 'nullable|string|max:255',
            'menus.*.*.children.*.url' => 'nullable|string|max:500',
            'menus.*.*.children.*.target' => 'nullable|in:_self,_blank',
            'menus.*.*.children.*.is_enabled' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['menus'] as $menu => $items) {
                if (! in_array($menu, self::MENUS, true)) {
                    continue;
                }

                DB::table('navigation_items')->where('menu', $menu)->delete();

                foreach (array_values($items) as $index => $item) {
                    $parentId = DB::table('navigation_items')->insertGetId($this->navRow($menu, $item, $index, null));

                    foreach (array_values($item['children'] ?? []) as $childIndex => $child) {
                        DB::table('navigation_items')->insert($this->navRow($menu, $child, $childIndex, $parentId));
                    }
                }
            }
        });

        return $this->adminShow();
    }

    private function navRow(string $menu, array $item, int $order, ?int $parentId): array
    {
        return [
            'menu' => $menu,
            'parent_id' => $parentId,
            'label' => $this->encode($item['label']),
            'url' => $this->nullableString($item['url'] ?? null),
            'page_slug' => $this->nullableString($item['page_slug'] ?? null),
            'target' => $item['target'] ?? '_self',
            'is_enabled' => (bool) ($item['is_enabled'] ?? true),
            'sort_order' => $order,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function tree(string $menu, bool $raw, ?string $lang): array
    {
        $rows = DB::table('navigation_items')
            ->where('menu', $menu)
            ->when(! $raw, fn ($query) => $query->where('is_enabled', true))
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $byParent = [];
        foreach ($rows as $row) {
            $byParent[(int) ($row->parent_id ?? 0)][] = $row;
        }

        $build = function (int $parentId) use (&$build, $byParent, $raw, $lang) {
            $items = [];
            foreach ($byParent[$parentId] ?? [] as $row) {
                $label = $this->decode($row->label);
                $item = [
                    'id' => $row->id,
                    'label' => $raw ? $label : Localizer::localize($label, (string) $lang),
                    'page_slug' => $row->page_slug,
                    'url' => $row->url,
                    'target' => $row->target,
                    'is_enabled' => (bool) $row->is_enabled,
                    'children' => $build((int) $row->id),
                ];
                $items[] = $item;
            }

            return $items;
        };

        return $build(0);
    }

    private function decode(mixed $value): mixed
    {
        if (! is_string($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    private function encode(mixed $value): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }

    private function nullableString(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : null;

        return $value === '' ? null : $value;
    }
}
