<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('link_url')->nullable();
            $table->enum('position', ['sidebar', 'header', 'footer', 'content', 'popup'])->default('sidebar');
            $table->enum('type', ['image', 'banner', 'popup', 'google_adsense'])->default('image');
            $table->text('google_adsense_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('show_to_registered')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adverts');
    }
};

