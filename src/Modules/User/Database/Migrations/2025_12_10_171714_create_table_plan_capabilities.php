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
        Schema::create('plan_capabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                ->constrained('plans')
                ->cascadeOnDelete();

            // e.g. 'max_business_profiles', 'online_payments', 'support_level'
            $table->string('key');

            // optional human label (e.g. "Logo Upload", "Support")
            $table->string('label')->nullable();

            // type hint so we can cast: 'bool', 'int', 'string', 'json'
            $table->string('type')->default('string');

            // raw value as string
            $table->text('value')->nullable();

            // optional JSON meta
            $table->json('meta')->nullable();

			$table->string('model_relationship')->nullable();

            $table->timestamps();

            $table->unique(['plan_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_capabilities');
    }
};
