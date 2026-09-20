<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Services become the single source of truth for the "areas of activity" cards:
 * each one gets a slug (matches its CMS detail page) and its own image, so the
 * public site no longer needs a hard-coded id => slug/image catalog.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'slug')) {
                $table->string('slug', 120)->nullable()->unique();
            }
            if (! Schema::hasColumn('services', 'image_url')) {
                $table->string('image_url')->nullable();
            }
        });

        // Backfill the five original services (they were addressed by order_index).
        $defaults = [
            1 => ['power-infrastructure', '/images/deck/power/switchboard-copper-3200kw.webp'],
            2 => ['industrial-infrastructure', '/images/deck/industrial/compressed-air-station.webp'],
            3 => ['led-displays', '/images/deck/led/concert-hall-screen.webp'],
            4 => ['refrigeration', '/images/deck/refrigeration/air-cooler.webp'],
            5 => ['sheet-metal-processing', '/images/deck/metalworks/laser-cutting.webp'],
        ];

        foreach ($defaults as $order => [$slug, $image]) {
            DB::table('services')
                ->where('order_index', $order)
                ->whereNull('slug')
                ->update(['slug' => $slug, 'image_url' => $image]);
        }
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'image_url']);
        });
    }
};
