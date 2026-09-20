<?php

namespace App\Http\Controllers\Api;

use App\Services\ImageUploadService;
use App\Support\Media;
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

    /** Newest first; used by the admin image picker. */
    public function index(): JsonResponse
    {
        $items = DB::table('media')
            ->orderByDesc('id')
            ->limit(300)
            ->get()
            ->map(fn ($row) => [
                'id' => $row->id,
                'url' => Media::url($row->path),
                'path' => $row->path,
                'filename' => $row->filename,
                'mime_type' => $row->mime_type,
                'size' => $row->size,
                'created_at' => $row->created_at,
            ]);

        return response()->json($items);
    }

    public function destroy(int $id): JsonResponse
    {
        $row = DB::table('media')->where('id', $id)->first();

        if (! $row) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $this->images->delete($row->path);
        DB::table('media')->where('id', $id)->delete();

        return response()->json(null, 204);
    }
}
