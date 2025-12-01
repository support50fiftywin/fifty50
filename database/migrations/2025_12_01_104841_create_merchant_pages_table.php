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
		Schema::table('users', function (Blueprint $table) {
			$table->string('business_name')->nullable()->after('name');
			$table->string('phone')->nullable()->after('business_name');
			$table->string('website')->nullable()->after('phone');
			$table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('website');
		});
	}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_pages');
    }
};
