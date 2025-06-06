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
        Schema::create('small_warehouse_libraries', function (Blueprint $table) {
            $table->id();
            $table->string('place');
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
        Schema::dropIfExists('small_warehouse_libraries');
    }
};
