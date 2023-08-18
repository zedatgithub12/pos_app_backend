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

        Schema::create('transfered_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_id');
            $table->string('item_name');
            $table->string('item_code');
            $table->string('item_unit');
            $table->decimal('item_price', 8, 2);
            $table->integer('existing_amount')->nullable();
            $table->integer('transfered_amount');
            $table->timestamps();

            $table->foreign('transfer_id')->references('id')->on('stock_transfers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfered_items');
    }
};