<?php

namespace App\Http\Controllers\Api;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->query('lang', 'en');
        $category = $request->query('category');

        $query = Gallery::orderBy('order_index');

        if ($category) {
            $query->category($category);
        }

        $images = $query->get();
        return response()->json(
            $images->map(fn($img) => $img->getTranslated($lang))
        );
    }

    public function categories()
    {
        $categories = Gallery::distinct('category')
            ->where('category', '!=', null)
            ->pluck('category')
            ->sort()
            ->values();

        return response()->json($categories);
    }

    // Admin endpoints
    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'title_hy' => 'string|nullable',
            'title_en' => 'string|nullable',
            'title_ru' => 'string|nullable',
            'image_url' => 'required|string',
            'thumbnail_url' => 'string|nullable',
            'category' => 'string|nullable',
            'order_index' => 'integer|nullable'
        ]);

        $image = Gallery::create($validated);
        return response()->json($image, 201);
    }

    public function update($id, Request $request)
    {
        $this->authorize('isAdmin');

        $image = Gallery::find($id);
        if (!$image) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'title_hy' => 'string|nullable',
            'title_en' => 'string|nullable',
            'title_ru' => 'string|nullable',
            'image_url' => 'string|nullable',
            'thumbnail_url' => 'string|nullable',
            'category' => 'string|nullable',
            'order_index' => 'integer|nullable'
        ]);

        $image->update($validated);
        return response()->json($image);
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $image = Gallery::find($id);
        if (!$image) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $image->delete();
        return response()->json(null, 204);
    }
}
