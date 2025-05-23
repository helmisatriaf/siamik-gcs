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
        Schema::create('mid_kindergartens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('class_teacher_id');
            $table->integer('english_language');
            $table->integer('mandarin_language');
            $table->integer('writing_skill');
            $table->integer('reading_skill');
            $table->integer('phonic');
            $table->integer('science');
            $table->integer('art_and_craft');
            $table->integer('physical_education');
            $table->integer('able_to_sit_quietly');
            $table->integer('willingness_to_listen');
            $table->integer('willingness_to_work');
            $table->integer('willingness_to_sing');
            $table->string('remarks');
            $table->integer('semester');
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
