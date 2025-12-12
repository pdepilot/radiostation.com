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
            $table->integer('spot')->nullable()->after('is_featured'); // 1-15 for ranking
            $table->boolean('is_sponsored')->default(false)->after('spot');
            $table->date('sponsor_start_date')->nullable()->after('is_sponsored');
            $table->date('sponsor_end_date')->nullable()->after('sponsor_start_date');
            $table->string('spotify_url')->nullable()->after('sponsor_end_date');
            $table->string('apple_music_url')->nullable()->after('spotify_url');
            $table->string('youtube_url')->nullable()->after('apple_music_url');
            $table->string('deezer_url')->nullable()->after('youtube_url');
            $table->decimal('sponsor_price', 10, 2)->nullable()->after('deezer_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playlist_tracks', function (Blueprint $table) {
            $table->dropColumn([
                'spot',
                'is_sponsored',
                'sponsor_start_date',
                'sponsor_end_date',
                'spotify_url',
                'apple_music_url',
                'youtube_url',
                'deezer_url',
                'sponsor_price',
            ]);
        });
    }
};
