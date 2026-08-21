<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->string('btn_primary')->nullable();
            $table->string('btn_primary_url')->nullable();
            $table->string('btn_secondary')->nullable();
            $table->string('btn_secondary_url')->nullable();
            $table->string('hero_bg_color')->nullable();
            $table->string('hero_text_color')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_maps')->nullable();
            $table->string('social_ig')->nullable();
            $table->string('social_fb')->nullable();
            $table->string('social_yt')->nullable();
            $table->string('social_wa')->nullable();
            $table->json('active_modules')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn([
                'btn_primary', 'btn_primary_url', 'btn_secondary', 'btn_secondary_url',
                'hero_bg_color', 'hero_text_color', 'contact_address', 'contact_phone',
                'contact_email', 'contact_maps', 'social_ig', 'social_fb', 'social_yt',
                'social_wa', 'active_modules',
            ]);
        });
    }
};