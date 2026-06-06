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
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
			// Business profiles belong to a workspace. The workspace carries user ownership.
			$table->unsignedBigInteger('workspace_id');
			$table->string('name');
			$table->string('legal_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_id')->nullable();   // VAT / EIN, etc.
            $table->string('license_no')->nullable();

            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();

			$table->string('logo_disk')->default('public');
            $table->string('logo_path')->nullable(); // e.g., s3 path
            $table->json('branding_json')->nullable(); // optional extra branding
			$table->tinyInteger('is_test')->default(0);
            $table->timestamps();
			$table->softDeletes();

			$table->foreign('workspace_id')
				->references('id')
				->on('workspace')
				->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};
