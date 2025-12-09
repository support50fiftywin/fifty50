<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
	{
		Schema::create('sweepstakes', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->string('prize_title');
			$table->string('image')->nullable();
			$table->date('start_date');
			$table->date('end_date');
			$table->enum('status', ['active', 'closed', 'scheduled'])->default('scheduled');
			$table->timestamps();
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sweepstakes');
    }
};
