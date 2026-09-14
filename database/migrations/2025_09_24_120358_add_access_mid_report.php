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
        Schema::table('master_academics', function(Blueprint $table) {
            $table->time('open_time_mid_report_card1')->after('report_card2')->nullable();
            $table->time('open_time_mid_report_card2')->nullable();
            $table->time('open_date_mid_report_card1')->nullable();
            $table->time('open_date_mid_report_card2')->nullable();
            $table->time('open_time_report_card1')->nullable();
            $table->time('open_time_report_card2')->nullable();
            $table->time('open_date_report_card1')->nullable();
            $table->time('open_date_report_card2')->nullable();
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
