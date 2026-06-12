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
        Schema::create('contact_messages', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('user_id')->unsigned()->nullable();
			$table->string('name')->nullable();
			$table->string('email')->nullable();
			$table->string('subject');
			$table->text('message');
			$table->timestamps();

			$table->foreign('user_id')->references('id')
				->on('users')
				->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
