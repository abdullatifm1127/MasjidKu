<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            $table->decimal('total_donasi', 15, 2)->default(0)->after('status');
            $table->decimal('target_donasi', 15, 2)->default(0)->after('total_donasi');
        });
    }

    public function down(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            $table->dropColumn(['total_donasi', 'target_donasi']);
        });
    }
};