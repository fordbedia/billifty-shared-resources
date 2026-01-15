<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Ports;

use Illuminate\Http\UploadedFile;

interface ImageProcessor
{
	public function resize(UploadedFile $file): string;

	public function store(UploadedFile $file): array;
}