<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel: donasis.
        Schema::table('donasis', function (Blueprint $table) {
            $table->string('keterangan', 200)->nullable()->after('nama_donatur');
        });
    }

    public function down(): void
    {
        Schema::table('donasis', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};