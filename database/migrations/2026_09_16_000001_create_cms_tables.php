<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void {
Schema::create('pages',function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->json('title');$t->json('seo')->nullable();$t->boolean('is_published')->default(true);$t->unsignedInteger('sort_order')->default(0);$t->timestamps();});
Schema::create('sections',function(Blueprint $t){$t->id();$t->foreignId('page_id')->constrained()->cascadeOnDelete();$t->string('key');$t->string('type')->default('content');$t->json('content');$t->json('settings')->nullable();$t->unsignedInteger('sort_order')->default(0);$t->boolean('is_active')->default(true);$t->timestamps();$t->unique(['page_id','key']);});
Schema::create('services',function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->json('title');$t->json('excerpt')->nullable();$t->json('body')->nullable();$t->string('cover')->nullable();$t->json('gallery')->nullable();$t->json('seo')->nullable();$t->unsignedInteger('sort_order')->default(0);$t->boolean('is_published')->default(true);$t->timestamps();});
Schema::create('projects',function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->json('title');$t->json('description')->nullable();$t->string('cover')->nullable();$t->json('gallery')->nullable();$t->json('meta')->nullable();$t->boolean('is_featured')->default(false);$t->boolean('is_published')->default(true);$t->date('published_at')->nullable();$t->timestamps();});
Schema::create('news',function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->json('title');$t->json('excerpt')->nullable();$t->json('body');$t->string('cover')->nullable();$t->json('seo')->nullable();$t->boolean('is_published')->default(false);$t->timestamp('published_at')->nullable();$t->timestamps();});
Schema::create('partners',function(Blueprint $t){$t->id();$t->string('name');$t->string('logo')->nullable();$t->string('url')->nullable();$t->unsignedInteger('sort_order')->default(0);$t->boolean('is_active')->default(true);$t->timestamps();});
Schema::create('settings',function(Blueprint $t){$t->id();$t->string('group')->default('general');$t->string('key')->unique();$t->json('value')->nullable();$t->timestamps();});
Schema::create('media',function(Blueprint $t){$t->id();$t->string('disk')->default('public');$t->string('path');$t->string('filename');$t->string('mime')->nullable();$t->unsignedBigInteger('size')->nullable();$t->json('alt')->nullable();$t->timestamps();});
} public function down(): void {foreach(['media','settings','partners','news','projects','services','sections','pages'] as $table) Schema::dropIfExists($table);} };
