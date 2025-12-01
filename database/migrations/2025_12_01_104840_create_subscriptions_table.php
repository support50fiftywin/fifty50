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
		Schema::create('subscriptions', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->string('stripe_subscription_id')->nullable();
			$table->string('package_name');
			$table->decimal('amount', 10, 2);
			$table->integer('entries_awarded')->default(0);
			$table->enum('status', ['active', 'cancelled', 'expired'])->default('active');
			$table->timestamps();
		});
	}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
