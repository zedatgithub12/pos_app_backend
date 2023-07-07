<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_targets', function (Blueprint $table) {
            $table->id();
            $table->integer("shopid");
            $table->string("shopname");
            $table->integer("s_daily");
            $table->integer("r_daily");
            $table->integer("s_monthly");
            $table->integer("r_monthly");
            $table->integer("s_yearly");
            $table->integer("r_yearly");
            $table->date("start_date");
            $table->date("end_date");
            $table->enum('status', ['active', 'inactive']);
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
