<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		if (!Schema::hasTable('workspace')) {
			return;
		}

		DB::table('users')
			->select('id')
			->orderBy('id')
			->chunkById(100, function ($users): void {
				foreach ($users as $user) {
					$existingDefault = DB::table('workspace')
						->where('user_id', $user->id)
						->where('is_default', 1)
						->first();

					if ($existingDefault) {
						continue;
					}

					$fallbackWorkspace = DB::table('workspace')
						->where('user_id', $user->id)
						->orderByDesc('is_default')
						->orderBy('id')
						->first();

					if ($fallbackWorkspace) {
						DB::table('workspace')
							->where('id', $fallbackWorkspace->id)
							->update([
								'name' => $fallbackWorkspace->name ?: 'default',
								'is_active' => 1,
								'is_default' => 1,
								'updated_at' => now(),
							]);

						continue;
					}

					DB::table('workspace')->insert([
						'user_id' => $user->id,
						'name' => 'default',
						'is_active' => 1,
						'is_default' => 1,
						'created_at' => now(),
						'updated_at' => now(),
					]);
				}
			});

		if (Schema::hasTable('invoices') && !Schema::hasColumn('invoices', 'workspace_id')) {
			Schema::table('invoices', function (Blueprint $table): void {
				$table->unsignedBigInteger('workspace_id')->nullable()->after('id');
			});
		}

		if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'workspace_id') && Schema::hasColumn('invoices', 'user_id')) {
			$defaultWorkspaceIds = DB::table('workspace')
				->where('is_default', 1)
				->pluck('id', 'user_id');

			DB::table('invoices')
				->select('id', 'user_id')
				->orderBy('id')
				->chunkById(100, function ($invoices) use ($defaultWorkspaceIds): void {
					foreach ($invoices as $invoice) {
						$workspaceId = $defaultWorkspaceIds[$invoice->user_id] ?? null;

						if (!$workspaceId) {
							continue;
						}

						DB::table('invoices')
							->where('id', $invoice->id)
							->whereNull('workspace_id')
							->update([
								'workspace_id' => $workspaceId,
								'updated_at' => now(),
							]);
					}
				});
		}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
