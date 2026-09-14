<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            // 1. Buat kolomnya dulu sebagai nullable (boleh kosong) dan tanpa foreign key langsung
            $table->unsignedBigInteger('mosque_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn('mosque_id');
        });
    }
};
