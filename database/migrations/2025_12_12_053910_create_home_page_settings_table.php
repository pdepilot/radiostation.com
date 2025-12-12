<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        \Illuminate\Support\Facades\DB::table('home_page_settings')->insert([
            ['key' => 'hero_title', 'value' => 'DARLING FM', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_subtitle', 'value' => 'OWERRI', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'live_stream_title', 'value' => '107.3 FM', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'live_stream_url', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'featured_sections_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_settings');
    }
};
