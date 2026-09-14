<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek satu-persatu agar tidak error jika kolomnya sudah ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('tenant_admin');
            }
            if (!Schema::hasColumn('users', 'masjid')) {
                $table->string('masjid')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_active_at')) {
                $table->timestamp('last_active_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'masjid', 'last_active_at']);
        });
    }
};