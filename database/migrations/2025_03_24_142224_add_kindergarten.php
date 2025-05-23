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
        Schema::table('mid_kindergartens', function (Blueprint $table) {
            $table->integer('brain_gym')->after('class_teacher_id')->nullable();
            $table->integer('cursive_writing')->after('brain_gym')->nullable();
            $table->integer('dictation')->after('cursive_writing')->nullable();
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
