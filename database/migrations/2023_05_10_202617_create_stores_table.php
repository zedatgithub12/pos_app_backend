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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('subcity')->nullable();
            $table->string('address');
            $table->string('phone');
            $table->text('description')->nullable();
            $table->text('profile_image')->nullable();
            $table->integer('manager_id')->nullable();
            $table->string('manager')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};