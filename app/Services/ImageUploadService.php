<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const MAX_SIZE = 10485760; // 10MB
    private const DISK = 'public';

    public function upload(UploadedFile $file, string $directory = 'uploads'): string
    {
        // Validate file
        if (!$this->isValidFile($file)) {
            throw new \Exception('Invalid file type or size');
        }

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Store file
        $path = Storage::disk(self::DISK)->putFileAs(
            $directory,
            $file,
            $filename
        );

        return '/storage/' . $path;
    }

    public function delete(string $path): bool
    {
        if (!$path) {
            return true;
        }

        $filePath = str_replace('/storage/', '', $path);
        
        return Storage::disk(self::DISK)->delete($filePath);
    }

    private function isValidFile(UploadedFile $file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            return false;
        }

        if ($file->getSize() > self::MAX_SIZE) {
            return false;
        }

        return true;
    }
}
