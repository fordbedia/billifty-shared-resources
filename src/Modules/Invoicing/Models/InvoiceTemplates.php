<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use Illuminate\Database\Eloquent\Model;

class InvoiceTemplates extends Model
{
    protected $table = 'invoice_templates';
		protected $guarded = [];

		public function category()
		{
			return $this->belongsTo(InvoiceTemplateCategory::class, 'invoice_template_category_id');
		}
}
