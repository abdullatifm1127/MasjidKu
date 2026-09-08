<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained('mosques')->onDelete('cascade');
            $table->string('judul');
            $table->text('isi');
            $table->enum('kategori', ['umum', 'ibadah', 'kegiatan', 'sosial', 'darurat'])->default('umum');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_penting')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumumans');
    }
};
