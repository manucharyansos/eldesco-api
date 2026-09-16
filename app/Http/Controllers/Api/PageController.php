<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query()->with('sections');

        if (!$request->user()) {
            $query->where('published', true);
        }

        return response()->json($query->orderBy('sort_order')->get());
    }

    public function show(string $slug, Request $request)
    {
        $query = Page::query()->with(['sections' => fn ($q) => $q->orderBy('sort_order')]);

        if (!$request->user()) {
            $query->where('published', true);
        }

        $page = $query->where('slug', $slug)->firstOrFail();

        if (!$request->user()) {
            $page->setRelation('sections', $page->sections->where('enabled', true)->values());
        }

        return response()->json($page);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => ['required', 'string', 'max:120', 'alpha_dash', 'unique:pages,slug'],
            'name' => ['required', 'string', 'max:255'],
            'seo_title' => ['nullable', 'array'],
            'seo_description' => ['nullable', 'array'],
            'published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'sections' => ['nullable', 'array'],
            'sections.*.key' => ['required_with:sections', 'string', 'max:120'],
            'sections.*.type' => ['required_with:sections', 'string', 'max:80'],
            'sections.*.content' => ['nullable', 'array'],
            'sections.*.image_url' => ['nullable', 'string', 'max:2048'],
            'sections.*.gallery' => ['nullable', 'array'],
            'sections.*.settings' => ['nullable', 'array'],
            'sections.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'sections.*.enabled' => ['sometimes', 'boolean'],
        ]);

        return DB::transaction(function () use ($validated) {
            $sections = $validated['sections'] ?? [];
            unset($validated['sections']);

            $page = Page::create($validated);
            foreach ($sections as $index => $section) {
                $section['sort_order'] = $section['sort_order'] ?? $index;
                $page->sections()->create($section);
            }

            return response()->json($page->load('sections'), 201);
        });
    }

    public function update(Page $page, Request $request)
    {
        $validated = $request->validate([
            'slug' => ['sometimes', 'string', 'max:120', 'alpha_dash', Rule::unique('pages', 'slug')->ignore($page->id)],
            'name' => ['sometimes', 'string', 'max:255'],
            'seo_title' => ['nullable', 'array'],
            'seo_description' => ['nullable', 'array'],
            'published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $page->update($validated);
        return response()->json($page->fresh('sections'));
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return response()->json(null, 204);
    }

    public function upsertSection(Page $page, Request $request, ?PageSection $section = null)
    {
        if ($section && $section->page_id !== $page->id) {
            abort(404);
        }

        $validated = $request->validate([
            'key' => ['required', 'string', 'max:120'],
            'type' => ['required', 'string', 'max:80'],
            'content' => ['nullable', 'array'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'gallery' => ['nullable', 'array'],
            'settings' => ['nullable', 'array'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'enabled' => ['sometimes', 'boolean'],
        ]);

        if ($section) {
            $section->update($validated);
        } else {
            $section = $page->sections()->updateOrCreate(
                ['key' => $validated['key']],
                $validated
            );
        }

        return response()->json($section, $section->wasRecentlyCreated ? 201 : 200);
    }

    public function deleteSection(Page $page, PageSection $section)
    {
        if ($section->page_id !== $page->id) {
            abort(404);
        }

        $section->delete();
        return response()->json(null, 204);
    }

    public function reorderSections(Page $page, Request $request)
    {
        $validated = $request->validate([
            'sections' => ['required', 'array'],
            'sections.*.id' => ['required', 'integer'],
            'sections.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($page, $validated) {
            foreach ($validated['sections'] as $item) {
                $page->sections()->whereKey($item['id'])->update(['sort_order' => $item['sort_order']]);
            }
        });

        return response()->json($page->fresh('sections'));
    }
}
