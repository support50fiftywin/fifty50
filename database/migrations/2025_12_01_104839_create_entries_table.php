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
		Schema::create('entries', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->foreignId('sweepstakes_id')->constrained()->cascadeOnDelete();
			$table->foreignId('merchant_id')->nullable()->constrained('users')->nullOnDelete();
			$table->enum('entry_source', ['stripe', 'clover', 'shopify', 'admin']);
			$table->string('payment_reference')->nullable();
			$table->boolean('confirmed')->default(false);
			$table->timestamps();
		});
	}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
