<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(): JsonResponse
    {
        $pages = Page::query()
            ->withCount('sections')
            ->orderBy('sort_order')
            ->get();

        return response()->json($pages);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validatePage($request);

        $page = DB::transaction(function () use ($data) {
            $page = Page::create([
                'slug' => $data['slug'],
                'title' => $data['title'],
                'seo' => $data['seo'] ?? [],
                'is_published' => $data['is_published'] ?? true,
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            $this->replaceSections($page, $data['sections'] ?? []);
            return $page;
        });

        return response()->json($this->loadPage($page), 201);
    }

    public function show(Page $page): JsonResponse
    {
        return response()->json($this->loadPage($page));
    }

    public function update(Request $request, Page $page): JsonResponse
    {
        $data = $this->validatePage($request, $page);

        DB::transaction(function () use ($page, $data) {
            $page->update([
                'slug' => $data['slug'],
                'title' => $data['title'],
                'seo' => $data['seo'] ?? [],
                'is_published' => $data['is_published'] ?? true,
                'sort_order' => $data['sort_order'] ?? 0,
            ]);

            if (array_key_exists('sections', $data)) {
                $this->replaceSections($page, $data['sections'] ?? []);
            }
        });

        return response()->json($this->loadPage($page->fresh()));
    }

    public function destroy(Page $page): JsonResponse
    {
        if ($page->slug === 'home') {
            return response()->json(['message' => 'The home page cannot be deleted.'], 422);
        }

        $page->delete();
        return response()->json(['message' => 'Page deleted.']);
    }

    private function validatePage(Request $request, ?Page $page = null): array
    {
        $pageId = $page?->id;

        return $request->validate([
            'slug' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9-]+$/', Rule::unique('pages', 'slug')->ignore($pageId)],
            'title' => ['required', 'array'],
            'title.hy' => ['nullable', 'string'],
            'title.en' => ['nullable', 'string'],
            'title.ru' => ['nullable', 'string'],
            'seo' => ['nullable', 'array'],
            'is_published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'sections' => ['nullable', 'array'],
            'sections.*.key' => ['required_with:sections', 'string', 'max:120'],
            'sections.*.type' => ['required_with:sections', Rule::in(['hero', 'rich_text', 'cards', 'list', 'gallery', 'logos', 'contact', 'cta', 'stats'])],
            'sections.*.title' => ['nullable', 'array'],
            'sections.*.subtitle' => ['nullable', 'array'],
            'sections.*.body' => ['nullable', 'array'],
            'sections.*.settings' => ['nullable', 'array'],
            'sections.*.media_id' => ['nullable', 'integer', 'exists:media,id'],
            'sections.*.is_enabled' => ['nullable', 'boolean'],
            'sections.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'sections.*.items' => ['nullable', 'array'],
            'sections.*.items.*.key' => ['nullable', 'string', 'max:120'],
            'sections.*.items.*.title' => ['nullable', 'array'],
            'sections.*.items.*.subtitle' => ['nullable', 'array'],
            'sections.*.items.*.body' => ['nullable', 'array'],
            'sections.*.items.*.meta' => ['nullable', 'array'],
            'sections.*.items.*.media_id' => ['nullable', 'integer', 'exists:media,id'],
            'sections.*.items.*.link_url' => ['nullable', 'string', 'max:500'],
            'sections.*.items.*.is_enabled' => ['nullable', 'boolean'],
            'sections.*.items.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function replaceSections(Page $page, array $sections): void
    {
        $page->sections()->delete();

        foreach (array_values($sections) as $sectionIndex => $sectionData) {
            /** @var Section $section */
            $section = $page->sections()->create([
                'key' => $sectionData['key'],
                'type' => $sectionData['type'],
                'title' => $sectionData['title'] ?? null,
                'subtitle' => $sectionData['subtitle'] ?? null,
                'body' => $sectionData['body'] ?? null,
                'settings' => $sectionData['settings'] ?? null,
                'media_id' => $sectionData['media_id'] ?? null,
                'is_enabled' => $sectionData['is_enabled'] ?? true,
                'sort_order' => $sectionData['sort_order'] ?? $sectionIndex,
            ]);

            foreach (array_values($sectionData['items'] ?? []) as $itemIndex => $itemData) {
                $section->items()->create([
                    'key' => $itemData['key'] ?? null,
                    'title' => $itemData['title'] ?? null,
                    'subtitle' => $itemData['subtitle'] ?? null,
                    'body' => $itemData['body'] ?? null,
                    'meta' => $itemData['meta'] ?? null,
                    'media_id' => $itemData['media_id'] ?? null,
                    'link_url' => $itemData['link_url'] ?? null,
                    'is_enabled' => $itemData['is_enabled'] ?? true,
                    'sort_order' => $itemData['sort_order'] ?? $itemIndex,
                ]);
            }
        }
    }

    private function loadPage(Page $page): Page
    {
        return $page->load([
            'sections' => fn ($query) => $query->orderBy('sort_order'),
            'sections.media',
            'sections.items' => fn ($query) => $query->orderBy('sort_order'),
            'sections.items.media',
        ]);
    }
}
