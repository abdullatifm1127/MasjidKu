<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mosque_notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('batch_id')->index();                 // mengelompokkan satu kali pengiriman
            $table->foreignId('mosque_id')->constrained('mosques')->cascadeOnDelete();
            $table->string('title', 120);
            $table->text('message');
            $table->string('type', 20)->default('info');        // info | success | warning | danger
            $table->string('url')->nullable();                  // tautan opsional saat notifikasi diklik
            $table->unsignedBigInteger('sent_by')->nullable();  // id user Super Admin pengirim
            $table->timestamp('read_at')->nullable();           // kosong = belum dibaca
            $table->timestamps();

            $table->index(['mosque_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mosque_notifications');
    }
};