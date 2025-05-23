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
        Schema::create('reference_books', function (Blueprint $table) {
            $table->id();
            $table->string('rack')->nullable();
            $table->string('no')->nullable();
            $table->string('name')->nullable();
            $table->string('author')->nullable();
            $table->string('publisher')->nullable(); 
            $table->integer('total')->nullable(); 
            $table->text('information')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_books');
    }
};
