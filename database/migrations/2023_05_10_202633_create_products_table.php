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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('picture')->nullable();
            $table->string('name');
            $table->string('category');
            $table->string('sub_category')->nullable();
            $table->string('brand')->nullable();
            $table->string('code');
            $table->decimal('cost', 8, 2);
            $table->string('unit');
            $table->decimal('price', 8, 2);
            $table->integer('min_quantity')->nullable();
            $table->integer('origional_quantity');
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->string('shop');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};