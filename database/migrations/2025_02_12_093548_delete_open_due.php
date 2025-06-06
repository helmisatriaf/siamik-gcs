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
        Schema::table('course_activities', function (Blueprint $table) {
            $table->dropColumn(['open_time', 'due_time']);
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
