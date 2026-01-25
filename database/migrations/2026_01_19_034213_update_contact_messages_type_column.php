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
        // Update enum to include all required values
        // MySQL requires dropping and recreating the enum, so we use raw SQL
        DB::statement("ALTER TABLE contact_messages MODIFY COLUMN type ENUM('general', 'advertising', 'shoutout', 'technical', 'event_partnership', 'feedback') DEFAULT 'general'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            // Revert to original enum values if needed
            DB::statement("ALTER TABLE contact_messages MODIFY COLUMN type ENUM('general', 'advertising', 'playlist', 'technical') DEFAULT 'general'");
        });
    }
};
