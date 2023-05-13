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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('user');
            $table->string('shop');
            $table->string('customer');
            $table->json('items');
            $table->decimal('tax', 8, 2);
            $table->decimal('discount', 8, 2);
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
        Schema::dropIfExists('sales');
    }
};