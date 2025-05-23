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
        Schema::create('cupboard_three_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('rack');
            $table->integer('no')->nullable();
            $table->string('name')->nullable();
            $table->integer('total')->nullable();
            $table->string('publisher')->nullable();
            $table->year('year')->nullable();
            $table->string('city')->nullable();
            $table->text('information')->nullable();
            $table->string('isbn')->nullable();
            $table->string('cover_image')->nullable(); // Menyimpan URL gambar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupboard_three_levels');
    }
};
