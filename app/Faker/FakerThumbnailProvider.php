<?php

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class FakerThumbnailProvider extends Base
{
    public function randomThumbnail(string $from, string $to, bool $fullPath = false): string
    {
        if (!Storage::exists($to)) {
            Storage::createDirectory($to);
        }

        $filePath = fake()->file(
            base_path('tests/Fixtures/' . $from),
            storage_path('app/public/' . $to),
            $fullPath
        );

        return 'storage/public/' . $to . '/' . $filePath;
    }
}
