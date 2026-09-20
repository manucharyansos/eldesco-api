<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use App\Support\Localizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $lang = $this->language($request);

        return Page::with(['sections' => fn ($q) => $q->where('is_enabled', true)->orderBy('sort_order')])
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Page $page) => $this->publicPage($page, $lang));
    }

    public function show(Request $request, string $slug)
    {
        $lang = $this->language($request);
        $page = Page::with(['sections' => fn ($q) => $q->where('is_enabled', true)->orderBy('sort_order')])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return response()->json($this->publicPage($page, $lang));
    }

    public function adminIndex()
    {
        return Page::with('sections')->orderBy('sort_order')->get();
    }

    public function adminShow(Page $page)
    {
        return response()->json($page->load('sections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|string|max:120|unique:pages,slug',
            'title' => 'nullable|array',
            'seo_title' => 'nullable|array',
            'seo_description' => 'nullable|array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
            'sections' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($data) {
            $sections = $data['sections'] ?? [];
            unset($data['sections']);
            $page = Page::create($data);

            foreach ($sections as $i => $section) {
                $page->sections()->create($this->sectionPayload($section, $i));
            }

            return $page->load('sections');
        });
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'slug' => 'sometimes|string|max:120|unique:pages,slug,'.$page->id,
            'title' => 'nullable|array',
            'seo_title' => 'nullable|array',
            'seo_description' => 'nullable|array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
            'sections' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($page, $data) {
            if (array_key_exists('sections', $data)) {
                $sections = $data['sections'] ?? [];
                unset($data['sections']);
                $page->sections()->delete();

                foreach ($sections as $i => $section) {
                    $page->sections()->create($this->sectionPayload($section, $i));
                }
            }

            $page->update($data);
            return $page->fresh('sections');
        });
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return response()->noContent();
    }

    public function storeSection(Request $request, Page $page)
    {
        $data = $this->validateSection($request);
        $section = $page->sections()->create($this->sectionPayload($data, (int) ($data['sort_order'] ?? 0)));

        return response()->json($section, 201);
    }

    public function updateSection(Request $request, PageSection $section)
    {
        $data = $this->validateSection($request, true);
        $section->update($data);

        return response()->json($section->fresh());
    }

    public function destroySection(PageSection $section)
    {
        $section->delete();
        return response()->noContent();
    }

    private function validateSection(Request $request, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return $request->validate([
            'type' => $required.'|string|max:80',
            'key' => 'nullable|string|max:120',
            'content' => 'nullable|array',
            'settings' => 'nullable|array',
            'is_enabled' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);
    }

    private function sectionPayload(array $section, int $fallbackOrder): array
    {
        return [
            'type' => $section['type'] ?? 'content',
            'key' => $section['key'] ?? null,
            'content' => $section['content'] ?? [],
            'settings' => $section['settings'] ?? [],
            'is_enabled' => $section['is_enabled'] ?? true,
            'sort_order' => $section['sort_order'] ?? $fallbackOrder,
        ];
    }

    private function publicPage(Page $page, string $lang): array
    {
        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'title' => $this->localize($page->title, $lang),
            'meta_title' => $this->localize($page->seo_title, $lang),
            'meta_description' => $this->localize($page->seo_description, $lang),
            'is_published' => $page->is_published,
            'sections' => $page->sections->map(fn (PageSection $section) => [
                'id' => $section->id,
                'type' => $section->type,
                'key' => $section->key,
                'content' => $this->localize($section->content ?? [], $lang),
                'settings' => $section->settings ?? [],
                'is_enabled' => $section->is_enabled,
                'sort_order' => $section->sort_order,
            ])->values(),
        ];
    }

    private function language(Request $request): string
    {
        return Localizer::language($request->query('lang'));
    }

    private function localize(mixed $value, string $lang): mixed
    {
        return Localizer::localize($value, $lang);
    }
}
