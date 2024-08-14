<?php

namespace App\Services;

use GuzzleHttp\Psr7\Stream;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadService
{

    public static function upload(UploadedFile $file, string $folder, $disk = 'public'): string

    {
        //filename withouth extension
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        //extension
        $extension = $file->getClientOriginalExtension();

        // add time to filename
        $filename = $filename . '-' . time() . '.' .$extension;

        return $file->storeAs($folder, $filename, $disk);

    }

    public static function delete(string $path, $disk = 'public'): bool
    {
        if (! Storage::disk($disk)->exists($path))
        {
            return false;
        }

        return (! Storage::disk($disk)->delete($path));

    }

    public static function url(string $path, string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($path);
    }
}
