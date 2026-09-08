<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            // Cek apakah kolom sudah ada, jika ingin aman kita bisa langsung drop jika ada, 
            // atau gunakan statement alternatif. Tapi cara paling bersih di sini:
            if (Schema::hasColumn('mosques', 'package_type')) {
                // Jika kolomnya sudah terlanjur berformat decimal, kita drop aman:
                $table->dropColumn('package_type');
            }
        });

        Schema::table('mosques', function (Blueprint $table) {
            // Buat kembali sebagai string
            $table->string('package_type')->default('free')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            if (Schema::hasColumn('mosques', 'package_type')) {
                $table->dropColumn('package_type');
            }
        });
    }
};