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
		Schema::create('invoice_reminder_schedules', function (Blueprint $table) {
			$table->id();
//			$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
//			$table->unsignedBigInteger('workspace_id')->nullable();
			$table->string('code')->nullable();
			// standard, gentle, custom, etc.

			$table->string('name');
			// Standard, Gentle, Custom

			$table->string('type')->default('system');
			// system, user, workspace, invoice_custom

			$table->boolean('is_active')->default(true);
//			$table->foreign('workspace_id')->references('id')->on('workspace')->cascadeOnDelete();

			$table->timestamps();
		});

		Schema::create('invoice_reminder_schedule_rules', function (Blueprint $table) {
			$table->id();

			$table->foreignId('invoice_reminder_schedule_id');

			$table->foreign('invoice_reminder_schedule_id', 'irs_rules_schedule_fk')
				->references('id')
				->on('invoice_reminder_schedules')
				->cascadeOnDelete();

			$table->integer('offset_days');
			// -3, 0, 3, 7, 14, 30

			$table->string('label');
			// 3 days before due date, On due date, 7 days after due date

			$table->string('channel')->default('email');
			// email for now

			$table->unsignedInteger('sort_order')->default(0);

			$table->boolean('is_active')->default(true);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('invoice_reminder_schedules');
		Schema::dropIfExists('invoice_reminder_schedule_rules');
	}
};
