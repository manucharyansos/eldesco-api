<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    private ImageUploadService $uploadService;

    public function __construct(ImageUploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function index(Request $request)
    {
        $lang = $request->query('lang', 'en');
        $featured = $request->query('featured');
        $category = $request->query('category');

        $query = Project::orderBy('order_index');

        if ($featured === 'true') {
            $query->where('featured', true);
        }

        if ($category) {
            $query->where('category', $category);
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

    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'title_hy' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_hy' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:10240',
            'category' => 'nullable|string|max:100',
            'featured' => 'boolean',
            'order_index' => 'nullable|integer'
        ]);

        if ($request->hasFile('image')) {
            $validated['image_url'] = $this->uploadService->upload(
                $request->file('image'),
                'projects'
            );
        }

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
            'title_hy' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ru' => 'nullable|string|max:255',
            'description_hy' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ru' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:10240',
            'category' => 'nullable|string|max:100',
            'featured' => 'nullable|boolean',
            'order_index' => 'nullable|integer'
        ]);

        if ($request->hasFile('image')) {
            if ($project->image_url) {
                $this->uploadService->delete($project->image_url);
            }
            $validated['image_url'] = $this->uploadService->upload(
                $request->file('image'),
                'projects'
            );
        }

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

        if ($project->image_url) {
            $this->uploadService->delete($project->image_url);
        }

        $project->delete();

        return response()->json(null, 204);
    }

    public function categories()
    {
        $categories = Project::distinct('category')
            ->where('category', '!=', null)
            ->pluck('category')
            ->sort()
            ->values();

        return response()->json($categories);
    }
}
