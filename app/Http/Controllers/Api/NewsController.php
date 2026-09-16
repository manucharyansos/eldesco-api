<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->query('lang', 'en');
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $news = News::published()
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $news->items() ? array_map(
                fn($n) => $n->getTranslated($lang),
                (array) $news->items()
            ) : [],
            'pagination' => [
                'total' => $news->total(),
                'per_page' => $news->perPage(),
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage()
            ]
        ]);
    }

    public function show($slugOrId, Request $request)
    {
        $lang = $request->query('lang', 'en');
        
        // Try to find by slug first, then by ID
        $news = News::where("slug_$lang", $slugOrId)
            ->orWhere('id', $slugOrId)
            ->published()
            ->first();

        if (!$news) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json($news->getTranslated($lang));
    }

    // Admin endpoints
    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'title_hy' => 'required|string',
            'title_en' => 'required|string',
            'title_ru' => 'string|nullable',
            'content_hy' => 'required|string',
            'content_en' => 'required|string',
            'content_ru' => 'string|nullable',
            'excerpt_hy' => 'string|nullable',
            'excerpt_en' => 'string|nullable',
            'excerpt_ru' => 'string|nullable',
            'image_url' => 'string|nullable',
            'published' => 'boolean'
        ]);

        $news = News::create($validated);
        return response()->json($news, 201);
    }

    public function update($id, Request $request)
    {
        $this->authorize('isAdmin');

        $news = News::find($id);
        if (!$news) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'title_hy' => 'string|nullable',
            'title_en' => 'string|nullable',
            'title_ru' => 'string|nullable',
            'content_hy' => 'string|nullable',
            'content_en' => 'string|nullable',
            'content_ru' => 'string|nullable',
            'excerpt_hy' => 'string|nullable',
            'excerpt_en' => 'string|nullable',
            'excerpt_ru' => 'string|nullable',
            'image_url' => 'string|nullable',
            'published' => 'boolean|nullable'
        ]);

        $news->update($validated);
        return response()->json($news);
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $news = News::find($id);
        if (!$news) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $news->delete();
        return response()->json(null, 204);
    }
}
