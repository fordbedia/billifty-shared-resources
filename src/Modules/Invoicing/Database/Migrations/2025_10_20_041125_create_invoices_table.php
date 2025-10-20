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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
						$table->unsignedInteger('user_id');
						$table->unsignedInteger('business_profile_id');
						$table->unsignedInteger('client_id');

						// Invoice identity
            $table->string('invoice_number'); // e.g., "INV-000123" (unique per user)
            $table->string('reference')->nullable(); // PO number or custom ref
            $table->string('currency', 3)->default('USD');

            // Dates & status
            $table->date('issued_on')->nullable();
            $table->date('due_on')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['draft','sent','viewed','partial','paid','void'])
                  ->default('draft');

            // Template snapshot (immutable for this invoice)
            $table->string('template_slug');
            $table->unsignedInteger('template_version')->default(1);
            $table->json('theme_json')->nullable(); // color scheme / toggles used

            // Totals (store in cents to avoid float errors)
            $table->bigInteger('subtotal_cents')->default(0);
            $table->bigInteger('discount_cents')->default(0); // absolute discount
            $table->decimal('discount_rate', 8, 4)->default(0); // % if you support it
            $table->bigInteger('tax_cents')->default(0);
            $table->bigInteger('shipping_cents')->default(0);
            $table->bigInteger('total_cents')->default(0);
            $table->bigInteger('amount_due_cents')->default(0);

            // Notes & attachments
            $table->text('notes')->nullable();      // visible to client
            $table->text('terms')->nullable();      // payment terms
            $table->string('pdf_url')->nullable();  // stored PDF snapshot (S3, etc.)
            $table->longText('render_snapshot_html')->nullable(); // optional HTML

            $table->json('meta')->nullable();
						$table->tinyInteger('is_test')->default(0);
            $table->timestamps();

						$table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
						$table->foreign('business_profile_id')->references('id')->on('business_profiles')->cascadeOnDelete();
						$table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
