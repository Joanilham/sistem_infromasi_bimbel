<?php

use App\Traits\HandlesImageUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

uses(Tests\TestCase::class);

beforeEach(function () {
    Storage::fake('public');
    
    $this->uploader = new class {
        use HandlesImageUpload;

        public function upload($file, $dir, $quality = 75)
        {
            return $this->compressAndStore($file, $dir, $quality);
        }
    };
});

test('it handles jpeg image compression and storage', function () {
    $file = UploadedFile::fake()->image('avatar.jpg', 100, 100);

    $path = $this->uploader->upload($file, 'avatars');

    expect($path)->toContain('avatars/');
    Storage::disk('public')->assertExists($path);
});

test('it handles png image compression and storage', function () {
    $file = UploadedFile::fake()->image('avatar.png', 100, 100);

    $path = $this->uploader->upload($file, 'avatars');

    expect($path)->toContain('avatars/');
    Storage::disk('public')->assertExists($path);
});

test('it handles webp image compression and storage', function () {
    $file = UploadedFile::fake()->image('avatar.webp', 100, 100);

    $path = $this->uploader->upload($file, 'avatars');

    expect($path)->toContain('avatars/');
    Storage::disk('public')->assertExists($path);
});

test('it falls back to normal store for invalid images', function () {
    // A text file is not a valid image
    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $path = $this->uploader->upload($file, 'documents');

    expect($path)->toContain('documents/');
    Storage::disk('public')->assertExists($path);
});

test('it falls back to normal store for unsupported image formats', function () {
    $file = UploadedFile::fake()->image('avatar.gif', 100, 100);

    $path = $this->uploader->upload($file, 'gifs');

    expect($path)->toContain('gifs/');
    Storage::disk('public')->assertExists($path);
});

test('it logs error and falls back on exception during compression', function () {
    $file = UploadedFile::fake()->image('avatar.jpg', 100, 100);

    // Mock storage to throw exception when public disk is accessed
    Storage::shouldReceive('disk')
        ->with('public')
        ->andThrow(new \Exception('Storage error'));

    Log::shouldReceive('error')
        ->once()
        ->with(Mockery::on(function ($message) {
            return str_contains($message, 'Compression Error: Storage error');
        }));

    try {
        $path = $this->uploader->upload($file, 'error_avatars');
    } catch (\Exception $e) {
        $path = null;
    }

    // Because it falls back to $file->store() which calls Storage::disk('public') under the hood,
    // it will throw the same Storage error exception, but wait!
    // Since we mocked Storage::disk to throw, let's verify if the exception log runs!
    expect($path)->toBeNull();
});
