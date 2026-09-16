<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function store(Request $request, ImageUploadService $uploadService)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
            'folder' => ['nullable', 'string', 'max:80', 'regex:/^[a-zA-Z0-9\-_]+$/'],
        ]);

        $url = $uploadService->upload(
            $validated['image'],
            'cms/' . ($validated['folder'] ?? 'general')
        );

        return response()->json(['url' => $url], 201);
    }
}
