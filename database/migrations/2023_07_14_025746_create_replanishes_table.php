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
        Schema::create('replanishes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("shop_id");
            $table->string("shop_name");
            $table->integer("user_id");
            $table->integer("stock_id");
            $table->string("stock_name");
            $table->integer("stock_code");
            $table->integer("existing_amount");
            $table->integer("added_amount");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replanishes');
    }
};