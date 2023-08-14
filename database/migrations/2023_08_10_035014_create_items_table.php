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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_image')->nullable();
            $table->string('item_name');
            $table->string('item_code');
            $table->string('item_category');
            $table->string('item_sub_category')->nullable();
            $table->string('item_brand');
            $table->string('item_unit');
            $table->decimal('item_price', 8, 2);
            $table->text('item_description')->nullable();
            $table->string('item_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};