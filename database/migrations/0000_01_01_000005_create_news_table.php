<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title_hy');
            $table->string('title_en')->nullable();
            $table->string('title_ru')->nullable();
            $table->string('slug_hy')->nullable()->unique();
            $table->string('slug_en')->nullable()->unique();
            $table->string('slug_ru')->nullable()->unique();
            $table->text('content_hy');
            $table->text('content_en')->nullable();
            $table->text('content_ru')->nullable();
            $table->string('excerpt_hy', 500)->nullable();
            $table->string('excerpt_en', 500)->nullable();
            $table->string('excerpt_ru', 500)->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('published')->default(true);
            $table->timestamps();

            $table->index('published');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
