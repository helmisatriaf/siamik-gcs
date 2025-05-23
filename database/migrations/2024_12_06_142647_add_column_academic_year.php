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
        Schema::table('teacher_grades', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('teacher_subjects', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('grade_subjects', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
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
