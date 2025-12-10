<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use BilliftySDK\SharedResources\Modules\User\Database\Seeders\PlansSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
		(new PlansSeeder)->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        (new PlansSeeder)->revert();
    }
};
