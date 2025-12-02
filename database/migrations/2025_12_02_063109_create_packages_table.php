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
			Schema::create('packages', function (Blueprint $table) {
				$table->id();
				$table->string('name'); // Bronze, Silver, Gold, Diamond
				$table->decimal('price', 10, 2);
				$table->integer('entries'); // 50, 200, 450, 1000
				$table->string('stripe_price_id')->nullable(); // price_xxx from Stripe
				$table->timestamps();
			});
		}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
