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
        Schema::create('color_scheme_color', function (Blueprint $table) {
            $table->id();
						$table->unsignedBigInteger('color_scheme_id');
						$table->string('name');
						$table->string('code');
            $table->timestamps();
						$table->foreign('color_scheme_id')->references('id')->on('color_scheme')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_scheme_color');
    }
};
