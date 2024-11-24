<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ThumbnailController extends Controller
{
    public function __invoke(
        string $dir,
        string $method,
        string $size,
        string $file
    ): JsonResponse|BinaryFileResponse {
        try {
            abort_if(
                !in_array($size, config('thumbnail.sizes'), true),
                403,
                'Invalid size'
            );

            $storage = Storage::disk('images');

            $path = "{$dir}/{$file}";
            $newDir = "{$dir}/{$method}/{$size}";
            $newPath = "{$newDir}/{$file}";

            if (!$storage->exists($newDir)) {
                $storage->makeDirectory($newDir);
            }

            if (!$storage->exists($newPath)) {
                $manager = new ImageManager(new Driver());

                $image = $manager->read($storage->path($path));
                $image->{$method}(...explode('x', $size));
                $image->save($storage->path($newPath));
            }

            return response()->file($storage->path($newPath));
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
}
