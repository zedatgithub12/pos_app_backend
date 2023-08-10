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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('item_code');
            $table->string('item_name');
            $table->string('stock_shop');
            $table->decimal('stock_cost', 8, 2)->nullable();
            $table->string('stock_unit');
            $table->integer('stock_min_quantity')->nullable();
            $table->decimal('stock_price', 8, 2);
            $table->integer('stock_quantity');
            $table->date('stock_expire_date')->nullable();
            $table->string('stock_status');
            $table->timestamps();

            $table->foreign('item_code')->references('item_code')->on('items')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};