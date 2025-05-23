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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('rack')->nullable();
            $table->string('rack_location')->nullable();
            $table->string('code')->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->string('category')->nullable();
            $table->string('publisher')->nullable();
            $table->year('year_published')->nullable();
            $table->string('cover_image')->nullable(); // Menyimpan URL gambar
            $table->text('description')->nullable();
            $table->integer('total');
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
