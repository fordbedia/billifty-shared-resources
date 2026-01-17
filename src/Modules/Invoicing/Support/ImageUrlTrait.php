<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Support;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait ImageUrlTrait
{
	protected function imageAttributeName(): string
	{
		return 'avatar';
	}

	public function imageUrl(): Attribute
	{
		return Attribute::make(get: function(): ?string {
			$attribute = $this->imageAttributeName();
			$value = $this->{$attribute};

			if (!is_string($value) || trim($value) === '') {
				return null;
			}
			// Already absolute URL
			if (preg_match('#^https?://#i', $value)) {
				return $value;
			}
			$base = rtrim(config('app.public_url', config('app.url')), '/');
			$path = ltrim($value, '/');
			return "{$base}/{$path}";
		});
	}
}