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
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mosque_id'); // Relasi ke tabel mosques
            $table->string('nama');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('rt_rw', 10)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'pindah', 'meninggal'])->default('aktif');
            $table->enum('peran', ['jamaah', 'pengurus', 'remaja', 'anak'])->default('jamaah');
            $table->date('tanggal_bergabung')->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();

            // Foreign key constraint ke tabel mosques
            $table->foreign('mosque_id')->references('id')->on('mosques')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};