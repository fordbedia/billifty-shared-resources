<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('paypal_webhook_events', function (Blueprint $table) {
			$table->id();
			$table->string('event_id')->unique();
			$table->string('type')->index();
			$table->string('resource_id')->nullable()->index();
			$table->json('payload');
			$table->timestamp('received_at')->useCurrent();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('paypal_webhook_events');
	}
};
