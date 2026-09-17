<?php

namespace App\Http\Controllers\Api;

use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MediaController extends Controller
{
    public function __construct(private readonly ImageUploadService $images)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'file', 'image', 'max:10240'],
            'alt_hy' => ['nullable', 'string', 'max:255'],
            'alt_en' => ['nullable', 'string', 'max:255'],
            'alt_ru' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('image');
        $path = $this->images->upload($file, 'cms');

        $id = DB::table('media')->insertGetId([
            'disk' => 'public',
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'alt' => json_encode([
                'hy' => $validated['alt_hy'] ?? '',
                'en' => $validated['alt_en'] ?? '',
                'ru' => $validated['alt_ru'] ?? '',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'id' => $id,
            'url' => url($path),
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
        ], 201);
    }
}
