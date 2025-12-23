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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();

            $table->string('plan_code');           // 'pro', 'premium'
            $table->string('billing_cycle');       // 'monthly', 'yearly'

            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->unique()->nullable();

            $table->string('currency', 10)->default('usd');
            $table->integer('unit_amount')->nullable();        // in cents (e.g. 499)
            $table->string('status')->default('active'); // 'active', 'past_due', etc.

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('renews_at')->nullable();
            $table->timestamp('cancels_at')->nullable();
            $table->timestamp('canceled_at')->nullable();

            $table->json('raw_payload')->nullable(); // optional: store Stripe blob

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
