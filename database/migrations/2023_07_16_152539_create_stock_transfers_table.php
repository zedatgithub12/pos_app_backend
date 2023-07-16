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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->integer("sendershopid");
            $table->string("sendershopname");
            $table->integer("receivershopid");
            $table->string("receivershopname");
            $table->json('items');
            $table->string("note")->nullable();
            $table->integer("userid");
            $table->enum('status', ['done', 'partially', 'failed']);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};