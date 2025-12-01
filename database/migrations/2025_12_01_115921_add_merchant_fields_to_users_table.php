<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
	{
		Schema::table('users', function (Blueprint $table) {

			if (!Schema::hasColumn('users', 'business_name')) {
				$table->string('business_name')->nullable();
			}
			if (!Schema::hasColumn('users', 'phone')) {
				$table->string('phone')->nullable();
			}
			if (!Schema::hasColumn('users', 'website')) {
				$table->string('website')->nullable();
			}
			if (!Schema::hasColumn('users', 'status')) {
				$table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
			}
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn(['business_name', 'phone', 'website', 'status']);
		});
	}
};
