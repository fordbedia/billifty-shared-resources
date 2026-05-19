<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
			$table->string('paypal_merchant_id')->nullable();
			$table->string('paypal_payer_id')->nullable();
			$table->string('paypal_email')->nullable();
			$table->boolean('paypal_enabled')->default(false);
			$table->boolean('paypal_payments_receivable')->default(false);
			$table->boolean('paypal_primary_email_confirmed')->default(false);
			$table->timestamp('paypal_onboarded_at')->nullable();
			$table->timestamp('paypal_disconnected_at')->nullable();
			$table->string('stripe_account_id')->nullable();
			$table->timestamp('stripe_connected_at')->nullable();
			$table->string('cash_app')->nullable();
			$table->text('notes')->nullable();
			$table->tinyInteger('is_test')->default(0);
			$table->timestamps();
			$table->softDeletes();
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
