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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active');
            $table->integer('semester');
            $table->unsignedBigInteger('type_exam');
            $table->foreign('type_exam')->references('id')->on('type_exams')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name_exam');
            $table->date('date_exam');
            $table->text('materi');
            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')->references('id')->on('teachers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
