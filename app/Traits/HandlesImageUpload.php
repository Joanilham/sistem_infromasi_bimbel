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
                    imagejpeg($image, $tempPath, $quality);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($file->getRealPath());
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    // PNG compression is 0-9. Level 7 is balanced.
                    imagepng($image, $tempPath, 7);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($file->getRealPath());
                    imagewebp($image, $tempPath, $quality);
                    break;
                default:
                    if (file_exists($tempPath)) unlink($tempPath);
                    return $file->store($directory, 'public');
            }

            if (isset($image)) {
                imagedestroy($image);
            }

            $filename = $file->hashName();
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
