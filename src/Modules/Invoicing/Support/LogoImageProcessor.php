<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class LogoImageProcessor
{
    /**
     * Resize a logo and store it on the given disk.
     *
     * - Normalizes to max 300x300 (no stretching, keeps aspect ratio).
     * - Always encodes to PNG for consistency.
     * - Returns ['logo_path' => '...', 'logo_disk' => '...'].
     */
    public static function resizeAndStore(
        UploadedFile $file,
        string $disk,
        string $baseDirectory = 'logo_path'
    ): array {
        $year = now()->year;
		$month = now()->month;

        // Generate a unique filename
        $hash = Str::random(40);
        $filename = "logo_{$hash}.png";
        $path = "{$baseDirectory}/{$year}/{$month}/{$filename}";

        // Create an ImageManager using the GD driver
        $manager = new ImageManager(new Driver());

        // Read from the uploaded file
        $image = $manager->read($file->getRealPath());

        // Optional: auto-rotate if EXIF orientation exists
        if (method_exists($image, 'alignRotation')) {
            $image->alignRotation();
        }

        // Resize without stretching (fit inside 300×300, keep aspect ratio)
        $image->scaleDown(
            width: 150,
            height: 150
        );

        // Encode as PNG (good balance for logos)
        $encoded = $image->toPng();

        // Store on the configured disk (local, s3, etc.)
        Storage::disk($disk)->put($path, (string) $encoded);

        return [
            'logo_path' => $path,
            'logo_disk' => $disk,
        ];
    }
}
