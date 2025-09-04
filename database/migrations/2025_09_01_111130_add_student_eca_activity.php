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
        Schema::create('student_eca_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('eca_id');
            $table->unsignedBigInteger('student_id');
            $table->foreign('eca_id')->references('id')->on('ecas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('semester')->nullable();
            $table->string('academic_year')->nullable();
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
