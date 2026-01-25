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
        // Drop post_likes table if it exists
        if (Schema::hasTable('post_likes')) {
            Schema::dropIfExists('post_likes');
        }

        // Remove like_count column from news_posts table if it exists
        if (Schema::hasColumn('news_posts', 'like_count')) {
            Schema::table('news_posts', function (Blueprint $table) {
                $table->dropColumn('like_count');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate post_likes table
        Schema::create('post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->unique(['news_post_id', 'user_id'], 'unique_user_like');
            $table->unique(['news_post_id', 'ip_address'], 'unique_ip_like');
        });

        // Re-add like_count column to news_posts table
        Schema::table('news_posts', function (Blueprint $table) {
            $table->unsignedInteger('like_count')->default(0)->after('comment_count');
        });
    }
};

