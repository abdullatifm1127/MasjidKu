<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mosque_id')->constrained('mosques')->onDelete('cascade');
            $table->decimal('amount', 12, 2)->default(0); // Nominal pembayaran
            $table->string('payment_proof')->nullable(); // File bukti transfer
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); 
            $table->timestamp('expired_date')->nullable(); // Masa aktif berakhir
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
