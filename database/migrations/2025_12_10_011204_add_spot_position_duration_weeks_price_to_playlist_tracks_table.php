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
        Schema::table('playlist_tracks', function (Blueprint $table) {
            $table->integer('spot_position')->nullable()->after('spot'); // 1-15 for monetization ranking
            $table->integer('duration_weeks')->nullable()->after('spot_position'); // Duration in weeks
            $table->decimal('price', 10, 2)->nullable()->after('duration_weeks'); // Price in Naira
            $table->json('platform_links')->nullable()->after('price'); // JSON object for platform links
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playlist_tracks', function (Blueprint $table) {
            $table->dropColumn([
                'spot_position',
                'duration_weeks',
                'price',
                'platform_links',
            ]);
        });
    }
};
