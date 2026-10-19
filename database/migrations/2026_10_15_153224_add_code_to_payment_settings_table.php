<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            // Cek dan tambahkan kolom jika belum ada
            if (!Schema::hasColumn('payment_settings', 'code')) {
                $table->string('code')->unique()->after('id')->nullable();
            }
            if (!Schema::hasColumn('payment_settings', 'config_data')) {
                $table->text('config_data')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn(['code', 'config_data']);
        });
    }
};