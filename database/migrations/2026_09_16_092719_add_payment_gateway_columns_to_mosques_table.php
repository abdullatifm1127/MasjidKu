<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            if (!Schema::hasColumn('mosques', 'order_id')) {
                $table->string('order_id')->nullable()->unique();
            }
            if (!Schema::hasColumn('mosques', 'payment_status')) {
                $table->string('payment_status')->default('unpaid');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'payment_status']);
        });
    }
};
