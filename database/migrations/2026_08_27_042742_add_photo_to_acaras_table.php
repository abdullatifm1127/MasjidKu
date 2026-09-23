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
        Schema::table('acaras', function (Blueprint $table) {
            // Cek dulu apakah kolom 'photo' sudah ada atau belum
            if (!Schema::hasColumn('acaras', 'photo')) {
                $table->string('photo')->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('acaras', function (Blueprint $table) {
            if (Schema::hasColumn('acaras', 'photo')) {
                $table->dropColumn('photo');
            }
        });
    }
};