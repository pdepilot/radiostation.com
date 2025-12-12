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
        Schema::table('audience_metrics', function (Blueprint $table) {
            $table->unsignedInteger('total_listening_sessions')->default(0)->after('average_listeners');
            $table->unsignedInteger('unique_listeners')->default(0)->after('total_listening_sessions');
            $table->unsignedInteger('total_listening_time')->default(0)->after('unique_listeners');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audience_metrics', function (Blueprint $table) {
            $table->dropColumn(['total_listening_sessions', 'unique_listeners', 'total_listening_time']);
        });
    }
};
