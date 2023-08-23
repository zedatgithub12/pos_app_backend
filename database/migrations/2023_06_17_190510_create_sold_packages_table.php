<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sold_packages', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('user');
            $table->string('shop');
            $table->string('customer');
            $table->string('p_name');
            $table->json('items');
            $table->string('payment_status');
            $table->string('payment_method');
            $table->text('note')->nullable();
            $table->decimal('grandtotal', 8, 2);
            $table->date('date');
            $table->time('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_packages');
    }
};