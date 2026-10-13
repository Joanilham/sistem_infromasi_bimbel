<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait HandlesImageUpload
{
    /**
     * Kompresi gambar menggunakan GD library sebelum disimpan.
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @param int $quality
     * @return string Path file yang disimpan
     */
    protected function compressAndStore($file, $directory, $quality = 75)
    {
        if (!extension_loaded('gd')) {
            return $file->store($directory, 'public');
        }

        $imageInfo = @getimagesize($file->getRealPath());
        if (!$imageInfo) {
            return $file->store($directory, 'public');
        }

        $mime = $imageInfo['mime'];
        $tempPath = null;

        try {
            $tempDirectory = storage_path('app/private');
            File::ensureDirectoryExists($tempDirectory);

            if (!is_writable($tempDirectory)) {
                throw new \RuntimeException('Temporary image directory is not writable.');
            }

            $tempPath = tempnam($tempDirectory, 'img_comp');
            if ($tempPath === false) {
                throw new \RuntimeException('Unable to create temporary image file.');
            }

            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($file->getRealPath());
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($file->getRealPath());
                    break;
                default:
                    return $file->store($directory, 'public');
            }

            // Convert and save EVERYTHING as WebP
            if (isset($image)) {
                imagewebp($image, $tempPath, $quality);
                imagedestroy($image);
            }

            // Generate a random filename with .webp extension
            $filename = \Illuminate\Support\Str::random(40) . '.webp';
            $finalPath = $directory . '/' . $filename;
            
            Storage::disk('public')->put($finalPath, file_get_contents($tempPath));
            
            return $finalPath;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Compression Error: ' . $e->getMessage());
            return $file->store($directory, 'public');
        } finally {
            if (is_string($tempPath) && file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }
}
