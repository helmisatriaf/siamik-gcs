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
        Schema::table('course_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('grade_subject_id')->after('section_id');
            $table->foreign('grade_subject_id')
                  ->references('id')
                  ->on('grade_subjects')
                  ->onDelete('cascade');
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
