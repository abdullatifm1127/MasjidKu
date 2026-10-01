<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_imams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained('mosques')->cascadeOnDelete();
            $table->string('prayer', 20);        // subuh | dzuhur | ashar | maghrib | isya
            $table->string('imam_name', 100);
            $table->timestamps();

            $table->unique(['mosque_id', 'prayer']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_imams');
    }
};