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
        Schema::create('sooa_primaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('class_teacher_id');
            $table->integer('academic')->nullable();
            $table->string('grades_academic')->nullable();
            $table->integer('choice')->nullable();
            $table->string('grades_choice')->nullable();
            $table->integer('language_and_art')->nullable();
            $table->string('grades_language_and_art')->nullable();
            $table->integer('self_development')->nullable();
            $table->string('grades_self_development')->nullable();
            $table->integer('eca_aver')->nullable();
            $table->string('grades_eca_aver')->nullable();
            $table->integer('behavior')->nullable();
            $table->string('grades_behavior')->nullable();
            $table->integer('attendance')->nullable();
            $table->string('grades_attendance')->nullable();
            $table->integer('participation')->nullable();
            $table->string('grades_participation')->nullable();
            $table->integer('final_score')->nullable();
            $table->string('grades_final_score')->nullable();
            $table->string('ranking')->nullable();
            $table->integer('semester')->nullable();
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('grade_id')->references('id')->on('grades')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('class_teacher_id')->references('id')->on('teachers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
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
