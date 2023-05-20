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
        Schema::create('price_updates', function (Blueprint $table) {
            $table->id();
            $table->integer('productid');
            $table->integer('shopid');
            $table->decimal('from', 8, 2);
            $table->decimal('to', 8, 2);
            $table->date('date');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_updates');
    }
};