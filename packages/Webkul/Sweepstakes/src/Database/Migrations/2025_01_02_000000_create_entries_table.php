<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sweepstake_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('sweepstake_id');
            $table->integer('customer_id')->nullable();
            $table->integer('merchant_id')->nullable();
            $table->integer('entries')->default(0);
            $table->enum('source', ['stripe','clover','shopify','admin'])->default('admin');
            $table->string('payment_reference')->nullable();
            $table->boolean('confirmed')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sweepstake_entries');
    }
};
