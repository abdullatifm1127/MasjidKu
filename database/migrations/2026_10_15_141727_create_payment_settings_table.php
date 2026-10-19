<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('method_name'); // 'Midtrans' atau 'Transfer Manual'
            $table->string('code')->unique(); // 'midtrans' atau 'manual'
            $table->boolean('is_active')->default(false);
            $table->text('config_data')->nullable(); // Untuk menyimpan JSON client key/server key Midtrans atau info rekening bank manual
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};