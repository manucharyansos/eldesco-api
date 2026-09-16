<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->query('lang', 'en');
        $featured = $request->query('featured');
        $category = $request->query('category');

        $query = Project::orderBy('order_index');

        if ($featured === 'true') {
            $query->featured();
        }

        if ($category) {
            $query->category($category);
        }

        $projects = $query->get();
        return response()->json(
            $projects->map(fn($p) => $p->getTranslated($lang))
        );
    }

    public function show($id, Request $request)
    {
        $lang = $request->query('lang', 'en');
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json($project->getTranslated($lang));
    }

    // Admin endpoints
    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'title_hy' => 'required|string',
            'title_en' => 'required|string',
            'title_ru' => 'string|nullable',
            'description_hy' => 'string|nullable',
            'description_en' => 'string|nullable',
            'description_ru' => 'string|nullable',
            'image_url' => 'string|nullable',
            'thumbnail_url' => 'string|nullable',
            'category' => 'string|nullable',
            'featured' => 'boolean',
            'order_index' => 'integer|nullable'
        ]);

        $project = Project::create($validated);
        return response()->json($project, 201);
    }

    public function update($id, Request $request)
    {
        $this->authorize('isAdmin');

        $project = Project::find($id);
        if (!$project) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'title_hy' => 'string|nullable',
            'title_en' => 'string|nullable',
            'title_ru' => 'string|nullable',
            'description_hy' => 'string|nullable',
            'description_en' => 'string|nullable',
            'description_ru' => 'string|nullable',
            'image_url' => 'string|nullable',
            'thumbnail_url' => 'string|nullable',
            'category' => 'string|nullable',
            'featured' => 'boolean|nullable',
            'order_index' => 'integer|nullable'
        ]);

        $project->update($validated);
        return response()->json($project);
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $project = Project::find($id);
        if (!$project) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $project->delete();
        return response()->json(null, 204);
    }
}
