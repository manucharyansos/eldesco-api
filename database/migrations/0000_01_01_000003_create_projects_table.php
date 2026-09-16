<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title_hy');
            $table->string('title_en')->nullable();
            $table->string('title_ru')->nullable();
            $table->text('description_hy')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ru')->nullable();
            $table->string('image_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('category')->nullable();
            $table->boolean('featured')->default(false);
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->index('featured');
            $table->index('category');
            $table->index('order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
