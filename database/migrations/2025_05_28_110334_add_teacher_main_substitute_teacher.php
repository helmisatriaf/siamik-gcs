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
        Schema::table('subtitute_teachers', function(Blueprint $table) {
            $table->unsignedBigInteger('teacher_main')->after('subject_id');
            $table->unsignedBigInteger('assistant_main')->after('teacher_main');
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
