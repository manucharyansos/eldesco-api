<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title_hy');
            $table->string('title_en')->nullable();
            $table->string('title_ru')->nullable();
            $table->longText('description_hy')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_ru')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->index('order_index');
            $table->fullText(['title_en', 'title_hy']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
