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
            $table->date('start_fe_1')->after('end_semester2')->nullable();
            $table->date('end_fe_1')->after('start_fe_1')->nullable();
            $table->date('start_fe_2')->after('end_fe_1')->nullable();
            $table->date('end_fe_2')->after('start_fe_2')->nullable();
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
