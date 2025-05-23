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
        Schema::table('master_academics', function (Blueprint $table) {
            $table->date('mid_report_card1')->nullable();
            $table->date('report_card1')->nullable();
            $table->date('mid_report_card2')->nullable();
            $table->date('report_card2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
