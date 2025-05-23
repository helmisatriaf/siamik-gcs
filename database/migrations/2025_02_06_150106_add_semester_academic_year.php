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
        Schema::table('sections', function (Blueprint $table) {
            $table->integer('semester')->after('file_path')->nullable();
            $table->string('academic_year')->after('semester')->nullable();
        });

        Schema::table('course_activities', function (Blueprint $table) {
            $table->integer('semester')->after('due_time')->nullable();
            $table->string('academic_year')->after('semester')->nullable();
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
