<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index()
    {
        return Page::with('sections')->where('is_published', true)->orderBy('sort_order')->get();
    }

    public function show(string $slug)
    {
        return Page::with(['sections' => fn($q) => $q->where('is_enabled', true)->orderBy('sort_order')])
            ->where('slug', $slug)->where('is_published', true)->firstOrFail();
    }

    public function adminIndex()
    {
        return Page::with('sections')->orderBy('sort_order')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|string|max:120|unique:pages,slug',
            'title' => 'nullable|array', 'seo_title' => 'nullable|array', 'seo_description' => 'nullable|array',
            'is_published' => 'boolean', 'sort_order' => 'integer', 'sections' => 'nullable|array'
        ]);

        return DB::transaction(function () use ($data) {
            $sections = $data['sections'] ?? []; unset($data['sections']);
            $page = Page::create($data);
            foreach ($sections as $i => $section) $page->sections()->create($section + ['sort_order' => $i]);
            return $page->load('sections');
        });
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'slug' => 'sometimes|string|max:120|unique:pages,slug,'.$page->id,
            'title' => 'nullable|array', 'seo_title' => 'nullable|array', 'seo_description' => 'nullable|array',
            'is_published' => 'boolean', 'sort_order' => 'integer', 'sections' => 'nullable|array'
        ]);

        return DB::transaction(function () use ($page, $data) {
            if (array_key_exists('sections', $data)) {
                $sections = $data['sections']; unset($data['sections']);
                $page->sections()->delete();
                foreach ($sections as $i => $section) $page->sections()->create($section + ['sort_order' => $i]);
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
}
