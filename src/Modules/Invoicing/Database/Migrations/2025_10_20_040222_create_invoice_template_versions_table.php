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
        Schema::create('invoice_template_versions', function (Blueprint $table) {
            $table->id();
			$table->foreignId('invoice_template_id')
				->constrained('invoice_templates')
				->cascadeOnDelete();
			$table->unsignedInteger('version');       // 1, 2, 3...
            $table->json('changelog')->nullable();    // optional notes
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_template_versions');
    }
};
