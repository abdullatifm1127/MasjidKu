<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'order_id')) {
                $table->string('order_id')->nullable()->unique()->after('mosque_id');
            }
            if (!Schema::hasColumn('subscriptions', 'package_type')) {
                $table->string('package_type')->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('subscriptions', 'transaction_status')) {
                $table->string('transaction_status')->nullable()->after('status'); // status mentah Midtrans
            }
            if (!Schema::hasColumn('subscriptions', 'payment_type')) {
                $table->string('payment_type')->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'transaction_id')) {
                $table->string('transaction_id')->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'fraud_status')) {
                $table->string('fraud_status')->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'raw_notification')) {
                $table->json('raw_notification')->nullable();
            }
        });

        // Bukti transfer tidak dipakai lagi, dan expired_date baru terisi setelah lunas.
        // (Laravel 10 ke bawah: butuh `composer require doctrine/dbal` untuk ->change())
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->change();
            $table->dateTime('expired_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
            $table->dropColumn([
                'order_id', 'package_type', 'transaction_status', 'payment_type',
                'transaction_id', 'fraud_status', 'paid_at', 'raw_notification',
            ]);
        });
    }
};