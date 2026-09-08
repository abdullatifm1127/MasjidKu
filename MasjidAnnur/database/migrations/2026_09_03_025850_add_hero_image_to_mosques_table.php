<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('mosques', 'hero_image')) {
            Schema::table('mosques', function (Blueprint $table) {
                $table->string('hero_image')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('mosques', 'hero_image')) {
            Schema::table('mosques', function (Blueprint $table) {
                $table->dropColumn('hero_image');
            });
        }
    }
};