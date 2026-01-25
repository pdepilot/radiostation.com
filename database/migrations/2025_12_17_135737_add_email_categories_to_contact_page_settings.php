<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add default email category settings if they don't exist
        $emailCategories = [
            ['key' => 'general_email', 'value' => 'info@darlingfm.ng', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'music_email', 'value' => 'music@darlingfm.ng', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'partnerships_email', 'value' => 'partners@darlingfm.ng', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($emailCategories as $category) {
            DB::table('contact_page_settings')->updateOrInsert(
                ['key' => $category['key']],
                $category
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove email category settings
        DB::table('contact_page_settings')
            ->whereIn('key', ['general_email', 'music_email', 'partnerships_email'])
            ->delete();
    }
};
