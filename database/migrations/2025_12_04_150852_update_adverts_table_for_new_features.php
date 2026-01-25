<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adverts', function (Blueprint $table) {
            if (!Schema::hasColumn('adverts', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('adverts', 'image_url')) {
                $table->string('image_url')->nullable()->after('description');
            }
            if (!Schema::hasColumn('adverts', 'google_adsense_code')) {
                $table->text('google_adsense_code')->nullable()->after('link_url');
            }
            if (!Schema::hasColumn('adverts', 'show_to_registered')) {
                $table->boolean('show_to_registered')->default(false)->after('is_active');
            }
            
            // Update type and position to enums if they're strings
            // Note: This is a simplified approach - in production you might want to handle this differently
        });
    }

    public function down(): void
    {
        Schema::table('adverts', function (Blueprint $table) {
            if (Schema::hasColumn('adverts', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('adverts', 'image_url')) {
                $table->dropColumn('image_url');
            }
            if (Schema::hasColumn('adverts', 'google_adsense_code')) {
                $table->dropColumn('google_adsense_code');
            }
            if (Schema::hasColumn('adverts', 'show_to_registered')) {
                $table->dropColumn('show_to_registered');
            }
        });
    }
};
