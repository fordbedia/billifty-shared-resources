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
        Schema::create('stripe_webhook_events', function (Blueprint $table) {
            $table->id();
			$table->foreignId('user_id')->nullable()->index()->constrained('users')->nullOnDelete();
			$table->string('stripe_customer_id')->nullable()->index();
			$table->string('stripe_subscription_id')->nullable()->index();
			$table->string('event_id')->unique();
			$table->string('type')->index();
			$table->string('api_version')->nullable();
			$table->string('livemode')->nullable();
			$table->json('payload');               // raw event JSON
			$table->timestamp('received_at')->useCurrent();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_webhook_events');
    }
};
