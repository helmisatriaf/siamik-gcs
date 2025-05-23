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
        Schema::table('acar_comments', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('acar_statuses', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('grade_exams', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('kindergartens', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('mid_kindergartens', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('mid_reports', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('nursery_toddlers', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('report_cards', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('report_card_statuses', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('score_attendances', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('score_attendance_statuses', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('score_kindergartens', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('scoring_statuses', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('sooa_primaries', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('sooa_secondaries', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('sooa_statuses', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('student_exams', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('subject_exams', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });

        Schema::table('tcops', function (Blueprint $table) {
            $table->string('academic_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
