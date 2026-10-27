<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasi_galeris', function (Blueprint $table) {
            $table->string('tujuan')->nullable()->after('deskripsi');   // rekening / tujuan penyaluran
            $table->json('rincian')->nullable()->after('tujuan');       // dipakai untuk apa saja
        });
    }

    public function down(): void
    {
        Schema::table('donasi_galeris', function (Blueprint $table) {
            $table->dropColumn(['tujuan', 'rincian']);
        });
    }
};