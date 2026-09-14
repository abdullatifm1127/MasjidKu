<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acaras', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mosque_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->date('event_date');
        $table->string('event_time')->nullable();      // mis. "16:00 WIB"
        $table->string('organizer')->nullable();        // mis. "Ustadz Yusuf Mansur" / "Panitia Masjid"
        $table->text('description')->nullable();        // untuk halaman detail acara
        $table->string('photo')->nullable();             // ← tambahkan ini
        $table->boolean('is_featured')->default(false);  // badge "Terbaru"
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('acaras');
    }
};