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
        Schema::create('shop_targets', function (Blueprint $table) {
            $table->id();
            $table->integer("userid");
            $table->integer("shopid");
            $table->string("shopname");
            $table->integer("r_daily");
            $table->integer("r_monthly");
            $table->integer("r_yearly");
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->enum('status', ['active', 'archived']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_targets');
    }
};