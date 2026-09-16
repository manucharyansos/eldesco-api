<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name_hy');
            $table->string('name_en')->nullable();
            $table->string('name_ru')->nullable();
            $table->string('position_hy')->nullable();
            $table->string('position_en')->nullable();
            $table->string('position_ru')->nullable();
            $table->string('image_url')->nullable();
            $table->string('email')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->index('order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
