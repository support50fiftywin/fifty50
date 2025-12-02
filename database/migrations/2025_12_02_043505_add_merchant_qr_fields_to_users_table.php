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
			$table->string('landing_slug')->nullable()->after('website');
			$table->string('qr_code')->nullable()->after('landing_slug');
		});
	}

	public function down(): void
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn(['landing_slug', 'qr_code']);
		});
	}

};
