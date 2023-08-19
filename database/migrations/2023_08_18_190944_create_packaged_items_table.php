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
        Schema::create('packaged_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages');
            $table->string('stock_id');
            $table->string('item_name');
            $table->string('item_code');
            $table->integer('item_quantity');
            $table->string('item_sku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packaged_items');
    }
};