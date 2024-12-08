<?php

namespace Tests\Feature\App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThumbnailControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('images');
    }

    public function test_thumbnail_is_created_and_returned_successfully(): void
    {
        $dir = 'uploads';
        $file = 'test-image.jpg';
        $method = 'resize';
        $size = '70x70';

        $originalImage = UploadedFile::fake()->image($file);
        Storage::disk('images')->put("$dir/$file", $originalImage->getContent());

        $response = $this->get(route('thumbnail', [
            'dir' => $dir,
            'method' => $method,
            'size' => $size,
            'file' => $file,
        ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/jpeg');

        $thumbnailPath = "$dir/$method/$size/$file";
        Storage::disk('images')->assertExists($thumbnailPath);
    }

    public function test_thumbnail_creation_fails_with_invalid_size(): void
    {
        $dir = 'uploads';
        $file = 'test-image.jpg';
        $method = 'resize';
        $size = '100x100';

        $originalImage = UploadedFile::fake()->image($file);
        Storage::disk('images')->put("$dir/$file", $originalImage->getContent());

        $response = $this->get(route('thumbnail', [
            'dir' => $dir,
            'method' => $method,
            'size' => $size,
            'file' => $file,
        ]));

        $response->assertForbidden();
        $response->assertJson(['error' => 'Invalid size']);

        $thumbnailPath = "$dir/$method/$size/$file";
        Storage::disk('images')->assertMissing($thumbnailPath);
    }

    public function test_thumbnail_creation_fails_when_original_file_is_missing(): void
    {
        $dir = 'uploads';
        $file = 'missing-image.jpg';
        $method = 'resize';
        $size = '70x70';

        $response = $this->get(route('thumbnail', [
            'dir' => $dir,
            'method' => $method,
            'size' => $size,
            'file' => $file,
        ]));

        $response->assertStatus(403);
    }
}
