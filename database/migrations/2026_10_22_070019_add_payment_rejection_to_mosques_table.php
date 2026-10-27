<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            if (!Schema::hasColumn('mosques', 'payment_note')) {
                $table->text('payment_note')->nullable()->after('payment_proof');
            }
            if (!Schema::hasColumn('mosques', 'payment_rejected_at')) {
                $table->timestamp('payment_rejected_at')->nullable()->after('payment_note');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            if (Schema::hasColumn('mosques', 'payment_rejected_at')) {
                $table->dropColumn('payment_rejected_at');
            }
            if (Schema::hasColumn('mosques', 'payment_note')) {
                $table->dropColumn('payment_note');
            }
        });
    }
};