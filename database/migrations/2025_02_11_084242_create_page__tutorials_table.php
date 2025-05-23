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
        Schema::create('page__tutorials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('media_type', ['video','image','text'])->default('text');
            $table->string('media_path')->nullable();
            $table->integer('order')->default(0);
            $table->string('element_selector')->nullable();
            $table->enum('position', ['top','bottom','left','right'])->default('bottom');
            $table->boolean('is_active')->default(true);
            $table->string('target_role')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            $table->string('created_by') ->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page__tutorials');
    }
};
