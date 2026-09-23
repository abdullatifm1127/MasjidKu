<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            // Hapus ->after('ewallet_account_name')
            $table->string('qris_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            $table->dropColumn('qris_image');
        });
    }
};