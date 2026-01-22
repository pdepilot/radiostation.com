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
        Schema::create('music_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('artist_name');
            $table->string('track_title');
            $table->text('description')->nullable();
            $table->string('audio_embed_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('cta_url')->nullable();
            $table->integer('duration_days'); // 7 or 14
            $table->decimal('price_paid', 10, 2);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->enum('status', ['pending', 'active', 'expired'])->default('pending');
            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->timestamps();
            
            $table->index('status');
            $table->index('ends_at');
            $table->index(['status', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_promotions');
    }
};
