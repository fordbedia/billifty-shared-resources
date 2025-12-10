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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            // e.g. 'free', 'pro', 'premium'
            $table->string('code')->unique();

            // e.g. 'Free', 'Pro', 'Premium'
            $table->string('name');

            $table->text('description')->nullable();

            // Pricing (monthly / yearly). Free will be 0.00.
            $table->decimal('price_monthly', 8, 2)->default(0);
            $table->decimal('price_yearly', 8, 2)->nullable();

            // Limits: null = unlimited
            $table->unsignedInteger('max_business_profiles')->nullable();
            $table->unsignedInteger('max_clients')->nullable();
            $table->unsignedInteger('max_invoices_per_month')->nullable();

            // Feature flags
            $table->boolean('pdf_watermark')->default(true);
            $table->boolean('email_watermark')->default(true);
            $table->boolean('allows_online_payments')->default(false);
            $table->boolean('allows_automated_reminders')->default(false);

            // If you want one plan to be the default for new users
            $table->boolean('is_default')->default(false);

            // Sorting in UI
            $table->unsignedTinyInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
