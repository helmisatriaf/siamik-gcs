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
        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('hasFile')->after('is_active');
            $table->string('file_name');
            $table->string('file_path');
        });
    }

    public function down(): void
    {
        //
    }
};
