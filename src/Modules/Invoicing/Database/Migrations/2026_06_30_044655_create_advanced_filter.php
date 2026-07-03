<?php

use BilliftySDK\SharedResources\Modules\Invoicing\Database\Seeders\AdvancedFilterSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('advanced_filter_fields', function (Blueprint $table) {
			$table->id();

			$table->string('module');

			$table->unsignedBigInteger('parent_id')->nullable();

			$table->string('field_key');
			$table->string('label');
			$table->string('group_label')->nullable();
			$table->string('data_type')->nullable();

			$table->boolean('has_sub_fields')->default(false);

			$table->string('default_sub_field_key')->nullable();
			$table->string('default_operator_key')->nullable();
			$table->string('value_source')->nullable();

			$table->boolean('is_enabled')->default(true);
			$table->unsignedInteger('sort_order')->default(0);

			$table->timestamps();

			$table->foreign('parent_id', 'adv_filter_fields_parent_fk')
				->references('id')
				->on('advanced_filter_fields')
				->cascadeOnDelete();

			$table->unique(
				['module', 'parent_id', 'field_key'],
				'adv_filter_fields_module_parent_field_unique'
			);

			$table->index(
				['module', 'parent_id', 'is_enabled', 'sort_order'],
				'adv_filter_fields_lookup_idx'
			);
		});

		Schema::create('advanced_filter_operators', function (Blueprint $table) {
			$table->id();

			$table->string('operator_key')->unique('adv_filter_operators_key_unique');

			$table->string('label');
			$table->string('value_component');

			$table->string('placeholder')->nullable();
			$table->string('value_source')->nullable();

			$table->boolean('requires_value')->default(true);
			$table->boolean('is_enabled')->default(true);
			$table->unsignedInteger('sort_order')->default(0);

			$table->timestamps();
		});

		Schema::create('advanced_filter_field_operators', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('advanced_filter_field_id');
			$table->unsignedBigInteger('advanced_filter_operator_id');

			$table->boolean('is_default')->default(false);

			$table->string('placeholder')->nullable();
			$table->string('value_source')->nullable();

			$table->unsignedInteger('sort_order')->default(0);

			$table->timestamps();

			$table->foreign(
				'advanced_filter_field_id',
				'adv_filter_field_ops_field_fk'
			)
				->references('id')
				->on('advanced_filter_fields')
				->cascadeOnDelete();

			$table->foreign(
				'advanced_filter_operator_id',
				'adv_filter_field_ops_operator_fk'
			)
				->references('id')
				->on('advanced_filter_operators')
				->cascadeOnDelete();

			$table->unique(
				[
					'advanced_filter_field_id',
					'advanced_filter_operator_id',
				],
				'adv_filter_field_operator_unique'
			);

			$table->index(
				['advanced_filter_field_id', 'sort_order'],
				'adv_filter_field_ops_field_sort_idx'
			);
		});

		Schema::create('saved_searches', function (Blueprint $table) {
			$table->id();

			$table->foreignId('user_id')
				->constrained()
				->cascadeOnDelete();

			$table->unsignedBigInteger('workspace_id')->nullable();

			$table->string('module');
			$table->string('name');

			$table->text('url')->nullable();

			$table->json('filters');

			$table->boolean('is_default')->default(false);

			$table->timestamps();

			$table->foreign('workspace_id')
				->references('id')
				->on('workspace')
				->onDelete('cascade');

			$table->index(
				['user_id', 'workspace_id', 'module'],
				'saved_searches_user_workspace_module_idx'
			);
		});

		$this->call([AdvancedFilterSeeder::class]);
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('saved_searches');
		Schema::dropIfExists('advanced_filter_field_operators');
		Schema::dropIfExists('advanced_filter_operators');
		Schema::dropIfExists('advanced_filter_fields');
	}
};