<?php

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class FakerThumbnailProvider extends Base
{
    /**
     * Generates a random thumbnail file from a source directory to a target directory.
     *
     * @param string $from The source directory relative to 'tests/Fixtures/'.
     * @param string $to The target directory relative to 'storage/app/public/'.
     * @param bool $fullPath Whether to return the full path of the file.
     * @return string The path to the generated thumbnail file.
     */
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
