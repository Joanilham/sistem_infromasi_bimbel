<?php

namespace App\Traits;

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
        $imageInfo = @getimagesize($file->getRealPath());
        if (!$imageInfo) {
            return $file->store($directory, 'public');
        }

        $mime = $imageInfo['mime'];
        $tempPath = tempnam(sys_get_temp_dir(), 'img_comp');

        try {
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
                    if (file_exists($tempPath)) unlink($tempPath);
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
            if (file_exists($tempPath)) unlink($tempPath);
            
            return $finalPath;
        } catch (\Exception $e) {
            if (file_exists($tempPath)) unlink($tempPath);
            \Illuminate\Support\Facades\Log::error('Compression Error: ' . $e->getMessage());
            return $file->store($directory, 'public');
        }
    }
}
