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
        Schema::create('subscription_packages', function (Blueprint $table) {
		$table->id();
		$table->string('name'); // Bronze, Silver, Gold, Diamond
		$table->decimal('price', 8, 2);
		$table->integer('entries');
		$table->string('stripe_price_id'); // stored after creation
		$table->boolean('active')->default(true);
		$table->timestamps();
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_packages');
    }
};
