<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use Illuminate\Database\Eloquent\Model;

class ColorScheme extends Model
{
    protected $table = 'color_scheme';
		protected $guarded = [];

		public function colors()
		{
			return $this->hasMany(ColorSchemeColor::class, 'color_scheme_id');
		}
}
