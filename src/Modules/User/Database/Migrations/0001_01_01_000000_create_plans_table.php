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
            $table->string('code')->unique();      // 'free', 'pro', 'premium'
            $table->string('name');                // 'Free', 'Pro', 'Premium'
            $table->text('description')->nullable();

            // pricing (optional)
            $table->decimal('price_monthly', 8, 2)->default(0);
            $table->decimal('price_yearly', 8, 2)->nullable();

            $table->boolean('is_default')->default(false);
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
