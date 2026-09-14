<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mosques', function (Blueprint $table) {
            $table->id();

            // Pemilik / pengguna yang mendaftarkan masjid
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Informasi Masjid
            $table->string('mosque_name');
            $table->string('arabic_name')->nullable();
            $table->string('tagline')->nullable();
            $table->integer('founded')->nullable();
            $table->string('capacity')->nullable();

            // Alamat
            $table->text('address');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('postal_code')->nullable();
            $table->string('city');
            $table->string('province');

            // Kontak
            $table->string('phone');
            $table->string('email');
            $table->string('website')->nullable();

            // Data Pengurus
            $table->string('organization_name')->nullable();

            $table->string('imam_name')->nullable();
            $table->string('imam_phone')->nullable();

            $table->string('chairman_name')->nullable();
            $table->string('chairman_phone')->nullable();

            $table->string('secretary_name')->nullable();
            $table->string('treasurer_name')->nullable();

            // Fasilitas & Program
            $table->json('facilities')->nullable();
            $table->json('programs')->nullable();

            $table->boolean('has_online_donation')->default(false);
            $table->boolean('has_prayer_schedule')->default(false);

            $table->text('description')->nullable();

            // Status Verifikasi
            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mosques');
    }
};