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
		Schema::create('color_scheme', function (Blueprint $table): void {
			$table->id();
			$table->string('color_scheme_name')->nullable();
			$table->string('slug');
			$table->string('color')->nullable();
			$table->string('preview_url')->nullable();
			$table->timestamps();
		});

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('business_profile_id');
			$table->unsignedInteger('client_id');
			$table->unsignedInteger('invoice_template_id');
			$table->unsignedInteger('color_scheme_id');
			$table->unsignedInteger('payment_information_id')->nullable();
			$table->unsignedInteger('currency_id');

						// BusinessProfile identity
            $table->string('invoice_number'); // e.g., "INV-000123" (unique per user)
            $table->string('reference')->nullable(); // PO number or custom ref
			$table->text('shipping_address')->nullable();

            // Dates & status
            $table->date('issued_on')->nullable();
			$table->date('issued_at')->nullable();
            $table->date('due_on')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['draft','issued','sent','partially','paid','void'])
                  ->default('draft');

            // Template snapshot (immutable for this invoice)
            $table->string('template_slug');
            $table->unsignedInteger('template_version')->default(1);
            $table->json('theme_json')->nullable(); // color scheme / toggles used

            // Totals (store in cents to avoid float errors)
            $table->bigInteger('subtotal_cents')->default(0);
			$table->enum('discount_mode', ['none','amount','percent','per-line'])
                  ->default('none');
            $table->bigInteger('discount_cents')->default(0); // absolute discount
            $table->decimal('discount_rate', 8, 2)->default(0); // % if you support it
            $table->bigInteger('tax_cents')->default(0);
            $table->bigInteger('shipping_cents')->default(0);
			$table->decimal('shipping_tax_rate', 6, 3)->default(0);
			$table->bigInteger('shipping_tax_cents')->default(0);
            $table->bigInteger('total_cents')->default(0);
            $table->bigInteger('amount_due_cents')->default(0);
			$table->timestamp('deleted_at')->nullable();

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
			$table->foreign('invoice_template_id')->references('id')->on('invoice_templates')->cascadeOnDelete();
			$table->foreign('color_scheme_id')->references('id')->on('color_scheme')->cascadeOnDelete();
			$table->foreign('payment_information_id')->references('id')->on('payment_information')->cascadeOnDelete();
			$table->foreign('currency_id')->references('id')->on('currency')->cascadeOnDelete();
			$table->index(['user_id', 'invoice_number'], 'invoices_user_invoice_unique');
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
