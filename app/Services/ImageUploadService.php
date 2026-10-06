<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ImageUploadService
{
    public static function saveAsWebp(
        UploadedFile $file,
        string $directory,
        string $basename,
        int $quality = 82,
        int $maxWidth = 1920,
        int $maxHeight = 1920
    ): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $relativeDirectory = trim($directory, '/');
        $targetDirectory = public_path($relativeDirectory);

        if (!is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        $safeBasename = Str::slug(pathinfo($basename, PATHINFO_FILENAME)) ?: 'image';

        if ($extension === 'svg') {
            $filename = $safeBasename . '.svg';
            $file->move($targetDirectory, $filename);

            return $relativeDirectory . '/' . $filename;
        }

        $image = self::createImageResource($file, $extension);
        if (!$image) {
            throw new InvalidArgumentException('File gambar tidak bisa dibaca.');
        }
        $filename = $safeBasename . '.webp';
        $targetPath = $targetDirectory . DIRECTORY_SEPARATOR . $filename;

        $image = self::resizeImage($image, $maxWidth, $maxHeight);

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        if (!imagewebp($image, $targetPath, $quality)) {
            imagedestroy($image);
            throw new InvalidArgumentException('Gagal mengkonversi gambar ke WebP.');
        }

        imagedestroy($image);

        return $relativeDirectory . '/' . $filename;
    }

    private static function createImageResource(UploadedFile $file, string $extension)
    {
        return match ($extension) {
            'jpg', 'jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'png' => imagecreatefrompng($file->getRealPath()),
            'gif' => imagecreatefromgif($file->getRealPath()),
            'webp' => imagecreatefromwebp($file->getRealPath()),
            default => throw new InvalidArgumentException('Format gambar tidak didukung untuk konversi WebP.'),
        };
    }

    private static function resizeImage($image, int $maxWidth, int $maxHeight)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxWidth && $height <= $maxHeight) {
            return $image;
        }

        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));
        $resized = imagecreatetruecolor($newWidth, $newHeight);

        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }
}
