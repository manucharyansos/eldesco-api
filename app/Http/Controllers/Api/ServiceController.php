<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->query('lang', 'en');
        $services = Service::orderBy('order_index')->get();
        
        return response()->json(
            $services->map(fn($s) => $s->getTranslated($lang))
        );
    }

    public function show($id, Request $request)
    {
        $lang = $request->query('lang', 'en');
        $service = ctype_digit((string) $id)
            ? Service::find($id)
            : Service::where('slug', $id)->first();

        if (!$service) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json($service->getTranslated($lang));
    }

    // Admin endpoints
    public function store(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:services,slug'],
            'title_hy' => 'required|string',
            'title_en' => 'required|string',
            'title_ru' => 'string|nullable',
            'description_hy' => 'string|nullable',
            'description_en' => 'string|nullable',
            'description_ru' => 'string|nullable',
            'icon' => 'string|nullable',
            'image_url' => 'string|nullable|max:500',
            'order_index' => 'integer|nullable'
        ]);

        $service = Service::create($validated);
        return response()->json($service, 201);
    }

    public function update($id, Request $request)
    {
        $this->authorize('isAdmin');

        $service = Service::find($id);
        if (!$service) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('services', 'slug')->ignore($service->id)],
            'title_hy' => 'string|nullable',
            'title_en' => 'string|nullable',
            'title_ru' => 'string|nullable',
            'description_hy' => 'string|nullable',
            'description_en' => 'string|nullable',
            'description_ru' => 'string|nullable',
            'icon' => 'string|nullable',
            'image_url' => 'string|nullable|max:500',
            'order_index' => 'integer|nullable'
        ]);

        $service->update($validated);
        return response()->json($service);
    }

    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $service = Service::find($id);
        if (!$service) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $service->delete();
        return response()->json(null, 204);
    }
}
