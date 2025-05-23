<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('grade_subject_id');
            $table->string('file_path');
            $table->integer('semester');
            $table->string('academic_year');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        //
    }
};
