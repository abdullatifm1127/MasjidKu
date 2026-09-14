<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained('mosques')->cascadeOnDelete();

            $table->string('key');        // slug unik per masjid, contoh: zakat, infaq-jumat
            $table->string('title');
            $table->text('description')->nullable();

            // 'zakat'   => tampil kalkulator fitrah/mal khusus
            // 'nominal' => tampil pilihan nominal bebas
            $table->enum('calc_type', ['zakat', 'nominal'])->default('nominal');

            $table->string('icon_key')->default('lainnya'); // lihat DonationCategory::iconLibrary()
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['mosque_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_categories');
    }
};