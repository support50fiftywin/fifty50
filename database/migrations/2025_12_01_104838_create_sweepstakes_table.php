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
		Schema::create('sweepstakes', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->string('prize_title');
			$table->string('prize_image')->nullable();
			$table->date('start_date');
			$table->date('end_date');
			$table->foreignId('winner_user_id')->nullable()->constrained('users')->nullOnDelete();
			$table->enum('status', ['active', 'closed', 'scheduled'])->default('scheduled');
			$table->timestamps();
		});
	}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sweepstakes');
    }
};
