<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Section;
use App\Models\SectionItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 40), 1), 100);
        return response()->json(Media::query()->latest()->paginate($perPage));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'image', 'max:12288'],
            'alt_hy' => ['nullable', 'string', 'max:255'],
            'alt_en' => ['nullable', 'string', 'max:255'],
            'alt_ru' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $validated['file'];
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $fileName = ($baseName ?: 'image').'-'.Str::lower(Str::random(8)).'.'.$file->getClientOriginalExtension();
        $directory = 'eldesco/'.now()->format('Y/m');
        $path = $file->storeAs($directory, $fileName, 'public');

        $media = Media::create([
            'disk' => 'public',
            'path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'alt' => [
                'hy' => $validated['alt_hy'] ?? '',
                'en' => $validated['alt_en'] ?? '',
                'ru' => $validated['alt_ru'] ?? '',
            ],
        ]);

        return response()->json($media, 201);
    }

    public function destroy(Media $media): JsonResponse
    {
        $inUse = Section::where('media_id', $media->id)->exists()
            || SectionItem::where('media_id', $media->id)->exists();

        if ($inUse) {
            return response()->json([
                'message' => 'This image is currently used on a page. Remove it from the page first.',
            ], 422);
        }

        Storage::disk($media->disk)->delete($media->path);
        $media->delete();

        return response()->json(['message' => 'Image deleted.']);
    }
}
