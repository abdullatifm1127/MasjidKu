<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            if (!Schema::hasColumn('mosques', 'zakat_nisab')) {
                $table->decimal('zakat_nisab', 15, 2)->default(85000000)->after('zakat_fitrah_default');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mosques', function (Blueprint $table) {
            if (Schema::hasColumn('mosques', 'zakat_nisab')) {
                $table->dropColumn('zakat_nisab');
            }
        });
    }
};