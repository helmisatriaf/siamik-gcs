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
        Schema::table('mid_reports', function (Blueprint $table) {
            $table->text('critical_thinking')->after('class_teacher_id');
            $table->text('cognitive_skills')->after('critical_thinking');
            $table->text('life_skills')->after('cognitive_skills');
            $table->text('learning_skills')->after('life_skills');
            $table->text('social_and_emotional_development')->after('learning_skills');
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
