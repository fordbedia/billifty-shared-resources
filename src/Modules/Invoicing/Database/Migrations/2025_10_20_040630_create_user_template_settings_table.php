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
        Schema::create('user_template_settings', function (Blueprint $table) {
            $table->id();
						$table->unsignedBigInteger('user_id');
						$table->unsignedBigInteger('business_profile_id')->nullable();
						// Default template + version the user prefers when creating invoices
            $table->string('default_template_slug')->nullable();
            $table->unsignedInteger('default_template_version')->nullable();

            // Default theme (color scheme, fonts, columns to show, etc.)
            $table->json('default_theme_json')->nullable();

            // Optional per-business-profile defaults
						$table->foreign('business_profile_id')->references('id')->on('business_profiles')->cascadeOnDelete();
						$table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();

						$table->index(['default_template_slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_template_settings');
    }
};
