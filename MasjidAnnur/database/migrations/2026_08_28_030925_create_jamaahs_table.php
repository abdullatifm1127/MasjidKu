<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained('mosques')->onDelete('cascade');

            // Identitas
            $table->string('nama');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nik', 20)->nullable();

            // Kontak
            $table->string('no_hp', 20)->nullable();
            $table->string('email')->nullable();

            // Alamat
            $table->text('alamat')->nullable();
            $table->string('rt_rw', 10)->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();

            // Status jamaah
            $table->enum('status', ['aktif', 'nonaktif', 'pindah', 'meninggal'])->default('aktif');
            $table->enum('peran', ['jamaah', 'pengurus', 'remaja', 'anak'])->default('jamaah');
            $table->date('tanggal_bergabung')->nullable();

            $table->string('foto')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};
