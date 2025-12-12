<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('live_chat_messages') && !Schema::hasColumn('live_chat_messages', 'is_moderated')) {
            Schema::table('live_chat_messages', function (Blueprint $table) {
                $table->boolean('is_moderated')->default(false)->after('ip_address');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('live_chat_messages', 'is_moderated')) {
            Schema::table('live_chat_messages', function (Blueprint $table) {
                $table->dropColumn('is_moderated');
            });
        }
    }
};
