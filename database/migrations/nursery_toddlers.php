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
        Schema::create('nursery_toddlers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('class_teacher_id');
            $table->integer('songs');
            $table->integer('prayer');
            $table->integer('colour');
            $table->integer('number');
            $table->integer('object');
            $table->integer('body_movement');
            $table->integer('colouring');
            $table->integer('painting');
            $table->integer('chinese_songs');
            $table->integer('ability_to_recognize_the_objects');
            $table->integer('able_to_own_up_to_mistakes');
            $table->integer('takes_care_of_personal_belongings_and_property');
            $table->integer('demonstrates_importance_of_self_control');
            $table->integer('management_emotional_problem_solving');
            $table->string('remarks');
            $table->integer('semester');
            $table->boolean('promote')->nullable();
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
