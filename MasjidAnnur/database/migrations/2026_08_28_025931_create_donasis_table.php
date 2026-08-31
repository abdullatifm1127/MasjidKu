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
            $table->foreignId('mosque_id')->constrained('mosques')->onDelete('cascade');

            // Identitas donatur
            $table->string('nama_donatur')->default('Hamba Allah');
            $table->string('no_hp', 20)->nullable();

            // Detail donasi
            $table->decimal('jumlah', 15, 2);
            $table->enum('kategori', ['infaq', 'sedekah', 'zakat', 'wakaf', 'lainnya'])->default('infaq');
            $table->string('keterangan')->nullable();

            // Metode & status pembayaran
            $table->enum('metode', ['tunai', 'transfer', 'qris', 'lainnya'])->default('tunai');
            $table->enum('status', ['lunas', 'pending', 'batal'])->default('lunas');

            $table->date('tanggal_donasi');
            $table->string('bukti_transfer')->nullable(); // path file
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donasis');
    }
};
