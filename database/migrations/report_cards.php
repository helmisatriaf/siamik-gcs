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
        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('class_teacher_id');
            $table->string('independent_work');
            $table->string('initiative');
            $table->string('homework_completion');
            $table->string('use_of_information');
            $table->string('cooperation_with_other');
            $table->string('conflict_resolution');
            $table->string('class_participation');
            $table->string('problem_solving');
            $table->string('goal_setting_to_improve_work');
            $table->string('strength_weakness_nextstep');
            $table->string('remarks');
            $table->integer('promotion_status');
            $table->integer('semester');
            $table->integer('academic_year');
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
