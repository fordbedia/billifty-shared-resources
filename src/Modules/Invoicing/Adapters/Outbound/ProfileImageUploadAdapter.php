<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Adapters\Outbound;

use BilliftySDK\SharedResources\Modules\Invoicing\Ports\ImageProcessor;
use BilliftySDK\SharedResources\Modules\Invoicing\Support\FileImageProcessor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class ProfileImageUploadAdapter implements ImageProcessor
{
	public function __construct(
        private readonly ImageProcessor $processor
    ) {}

	public static function make(?string $disk = null): self
    {
        $disk ??= config('filesystems.default', 'public');

        return new self(
            new FileImageProcessor(
                width: 150,
                height: 150,
                disk: $disk,
                baseDirectory: 'profile_images'
            )
        );
    }

	public function resize(UploadedFile $file): string
	{
		return $this->processor->resize($file);
	}

	public function store(UploadedFile $file): array
	{
		return $this->processor->store($file);
	}

	/** @adapter Adapts delete method
	 * Deletes the last record or item from the applicable dataset or resource.
	 */
	public function deleteLast(Model $model, string $column = 'avatar'): bool
	{
		return $this->processor->delete($model->{$column});
	}
}