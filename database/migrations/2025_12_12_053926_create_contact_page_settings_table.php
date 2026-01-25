<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        \Illuminate\Support\Facades\DB::table('contact_page_settings')->insert([
            ['key' => 'page_title', 'value' => 'Contact Us', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'office_address', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'map_url', 'value' => 'https://maps.app.goo.gl/qPWKXDAngcD8thcc9', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_page_settings');
    }
};
