<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
				Schema::create('invoice_template_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();         // 'modern', 'classic', 'minimal'
            $table->string('display_name');           // 'Modern', 'Classic', 'Minimal'
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
        Schema::create('invoice_templates', function (Blueprint $table) {
            $table->id();
						$table->unsignedInteger('invoice_template_category_id')->nullable();
						$table->string('slug')->unique();            // e.g., "classic", "modern"
            $table->string('display_name');              // “Classic”, “Modern”
            $table->unsignedInteger('current_version')->default(1);
            $table->string('preview_url')->nullable();   // CDN image for gallery
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();        // anything else
            $table->timestamps();

						$table->foreign('invoice_template_category_id')
							->references('id')
							->on('invoice_template_categories')
							->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
				Schema::dropIfExists('invoice_template_categories');
        Schema::dropIfExists('invoice_templates');
    }
};
