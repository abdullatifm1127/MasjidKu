<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eid_prayers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained('mosques')->cascadeOnDelete();
            $table->string('title', 120);              // mis. "Shalat Idul Fitri 1448 H"
            $table->date('event_date');
            $table->string('prayer_time', 5);          // format HH:MM
            $table->string('location', 150)->nullable();
            $table->string('imam_name', 100)->nullable();
            $table->string('khatib_name', 100)->nullable();
            $table->string('notes', 500)->nullable();
            $table->timestamps();

            $table->index(['mosque_id', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eid_prayers');
    }
};