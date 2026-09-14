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
    Schema::table('landing_pages', function (Blueprint $table) {
        $table->string('hero_background')->nullable()->after('id'); // Menambahkan kolom hero_background
    });
}

public function down(): void
{
    Schema::table('landing_pages', function (Blueprint $table) {
        $table->dropColumn('hero_background');
    });
}
};
