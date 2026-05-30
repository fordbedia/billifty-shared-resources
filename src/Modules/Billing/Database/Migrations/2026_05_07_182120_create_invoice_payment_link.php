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
        Schema::create('payment_link', function (Blueprint $table) {
            $table->id();
			$table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
			$table->string('token')->unique()->index();
			$table->string('paypal_order_id')->nullable()->index();
			$table->string('paypal_capture_id')->nullable()->index();
			$table->timestamp('public_token_expires_at')->nullable();
			$table->timestamp('public_token_revoked_at')->nullable();
			$table->timestamp('expires_at');
			$table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_link');
    }
};
