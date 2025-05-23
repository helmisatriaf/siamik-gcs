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
        Schema::table('page__tutorials', function (Blueprint $table) {
            $table->dropColumn('page_name');

            // Add the foreign key column
            $table->foreignId('page_id')->after('id')->constrained('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page__tutorials', function (Blueprint $table) {
            //
        });
    }
};
