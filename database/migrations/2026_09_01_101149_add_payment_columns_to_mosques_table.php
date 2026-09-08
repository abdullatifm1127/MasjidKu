<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('mosques', function (Blueprint $table) {
        // Status pembayaran: 'unpaid' (belum bayar), 'pending' (sudah upload, tunggu verifikasi), 'paid' (lunas)
        $table->string('payment_status')->default('unpaid');
        $table->string('payment_proof')->nullable(); // Path foto bukti transfer
    });
}

public function down()
{
    Schema::table('mosques', function (Blueprint $table) {
        $table->dropColumn(['payment_status', 'payment_proof']);
    });
}
};
