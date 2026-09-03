<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mosque_id')
                ->constrained('mosques')
                ->cascadeOnDelete();

            // 'zakat', 'infaq', 'sedekah', 'pembangunan', 'yatim', 'bencana', 'wakaf', 'qurban', 'lainnya'
            $table->string('jenis');

            // hanya diisi kalau jenis = 'zakat': 'fitrah' atau 'mal'
            $table->string('zakat_subtype')->nullable();

            $table->unsignedBigInteger('nominal');

            $table->string('nama_donatur')->default('Hamba Allah');

            // 'QRIS', 'Transfer Bank', 'Dompet Digital'
            $table->string('metode_pembayaran');

            $table->string('no_referensi')->unique();

            // 'pending' saat dibuat, diupdate ke 'paid'/'failed' lewat webhook payment gateway
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasis');
    }
};