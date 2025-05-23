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
        Schema::create('master_academics', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year');
            $table->date('semester1');
            $table->date('end_semester1');
            $table->date('semester2');
            $table->date('end_semester2');
            $table->integer('now_semester');
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
