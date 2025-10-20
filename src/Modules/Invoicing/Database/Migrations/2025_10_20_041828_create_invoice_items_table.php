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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
						$table->unsignedBigInteger('invoice_id');
						$table->unsignedInteger('position')->default(1); // for ordering
            $table->string('name')->nullable();               // short label
            $table->text('description')->nullable();

            $table->decimal('quantity', 12, 4)->default(1);
            $table->string('unit')->nullable();               // hr, pc, etc.

            // Monetary (in cents)
            $table->bigInteger('unit_price_cents')->default(0);
            $table->bigInteger('line_discount_cents')->default(0); // absolute
            $table->decimal('line_discount_rate', 8, 4)->default(0); // % if used
            $table->decimal('tax_rate', 8, 4)->default(0);     // percent e.g., 8.25
            $table->bigInteger('tax_cents')->default(0);
            $table->bigInteger('line_total_cents')->default(0);

            $table->json('meta')->nullable();
            $table->timestamps();
						$table->foreign('invoice_id')->references('id')->on('invoices')->cascadeOnDelete();
						$table->index(['position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
