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
        Schema::create('payment_information', function (Blueprint $table) {
            $table->id();
			$table->enum('payment_method', [
				'bank_transfer',
				'paypal',
				'stripe',
				'cash_app'
			])->nullable();
			$table->string('bank_name')->nullable();
			$table->string('account_name')->nullable();
			$table->string('account_number')->nullable();
			$table->string('routing_number')->nullable();
			$table->string('iban')->nullable();
			$table->string('swift_code')->nullable();
			$table->string('paypal_email')->nullable();
			$table->string('cash_app')->nullable();
			$table->text('notes')->nullable();
			$table->tinyInteger('is_test')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_information');
    }
};
