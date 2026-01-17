<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Support;

use BilliftySDK\SharedResources\Modules\Invoicing\Ports\ImageProcessor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FileImageProcessor implements ImageProcessor
{
	protected $manager;

	public function __construct(
		protected int $width,
		protected int $height,
		protected string $disk,
		protected string $baseDirectory = 'logo_path',
	) {
		$this->manager = new ImageManager(new Driver());
	}

	public function resize(UploadedFile $file): string
	{
        $image = $this->manager->read($file->getRealPath());

        if (method_exists($image, 'alignRotation')) {
            $image->alignRotation();
        }

        $image->scaleDown(width: $this->width, height: $this->height);

        // Intervention v3 returns an EncodedImage object; cast to string for Storage::put
        return (string) $image->toPng();
	}

	public function store(UploadedFile $file): array
	{
		$year = now()->year;
        $month = now()->month;

        $hash = Str::random(40);
        $filename = "logo_{$hash}.png";
        $path = "{$this->baseDirectory}/{$year}/{$month}/{$filename}";

        $encoded = $this->resize($file);

        Storage::disk($this->disk)->put($path, $encoded);

        return [
            'logo_path' => $path,
            'logo_disk' => $this->disk,
        ];
	}
}