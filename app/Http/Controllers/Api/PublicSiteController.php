<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicSiteController extends Controller
{
    public function site(Request $request): JsonResponse
    {
        $locale = $this->locale($request);

        $settings = Setting::query()
            ->where('is_public', true)
            ->orderBy('group')
            ->get()
            ->mapWithKeys(fn (Setting $setting) => [$setting->key => $setting->value]);

        $navigation = Page::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get(['id', 'slug', 'title'])
            ->map(fn (Page $page) => [
                'id' => $page->id,
                'slug' => $page->slug,
                'title' => $this->translated($page->title, $locale),
            ]);

        return response()->json([
            'locale' => $locale,
            'supported_locales' => ['hy', 'en', 'ru'],
            'settings' => $settings,
            'navigation' => $navigation,
        ]);
    }

    public function pages(): JsonResponse
    {
        return response()->json(
            Page::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->get(['id', 'slug', 'title', 'seo'])
        );
    }

    public function page(string $slug): JsonResponse
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with([
                'sections' => fn ($query) => $query->where('is_enabled', true)->orderBy('sort_order'),
                'sections.media',
                'sections.items' => fn ($query) => $query->where('is_enabled', true)->orderBy('sort_order'),
                'sections.items.media',
            ])
            ->firstOrFail();

        return response()->json($page);
    }

    private function locale(Request $request): string
    {
        $locale = (string) $request->query('locale', 'hy');
        return in_array($locale, ['hy', 'en', 'ru'], true) ? $locale : 'hy';
    }

    private function translated(?array $value, string $locale): ?string
    {
        if (!$value) {
            return null;
        }

        return $value[$locale] ?? $value['hy'] ?? $value['en'] ?? collect($value)->first();
    }
}
