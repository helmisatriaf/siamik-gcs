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
        Schema::create('cupboard_cd_books', function (Blueprint $table) {
            $table->id();
            $table->integer('cupboard');
            $table->integer('rack')->nullable();
            $table->integer('no')->nullable();
            $table->string('name')->nullable();
            $table->integer('total')->nullable();
            $table->string('cover_image')->nullable(); // Menyimpan URL gambar
            $table->text('information')->nullable();
            $table->text('progress')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        //
    }
};
