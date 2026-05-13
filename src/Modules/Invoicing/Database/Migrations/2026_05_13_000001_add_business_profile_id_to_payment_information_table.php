<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('payment_information', function (Blueprint $table) {
			if (!Schema::hasColumn('payment_information', 'business_profile_id')) {
				$table->unsignedBigInteger('business_profile_id')->nullable()->after('id');
				$table->index(['business_profile_id', 'payment_method'], 'payment_information_profile_method_index');
			}
		});

		DB::table('business_profiles')
			->whereNotNull('payment_information_id')
			->orderBy('id')
			->select(['id', 'payment_information_id'])
			->chunkById(100, function ($businessProfiles) {
				foreach ($businessProfiles as $businessProfile) {
					DB::table('payment_information')
						->where('id', $businessProfile->payment_information_id)
						->whereNull('business_profile_id')
						->update(['business_profile_id' => $businessProfile->id]);
				}
			});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('payment_information', function (Blueprint $table) {
			if (Schema::hasColumn('payment_information', 'business_profile_id')) {
				$table->dropIndex('payment_information_profile_method_index');
				$table->dropColumn('business_profile_id');
			}
		});
	}
};
