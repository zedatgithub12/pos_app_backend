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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shopid');
            $table->string('shopname');
            $table->unsignedBigInteger('userid');
            $table->string('name');
            $table->json('items');
            $table->decimal('price', 8, 2);
            $table->date('expiredate');
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();

            $table->foreign('shopid')->references('id')->on('stores');
            $table->foreign('userid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};