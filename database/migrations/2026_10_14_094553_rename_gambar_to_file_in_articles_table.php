<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('articles', function (Blueprint $table) {
        $table->renameColumn('gambar', 'file');
        $table->string('file_nama')->nullable()->after('gambar'); // nama asli file untuk ditampilkan
    });
}

public function down(): void
{
    Schema::table('articles', function (Blueprint $table) {
        $table->dropColumn('file_nama');
        $table->renameColumn('file', 'gambar');
    });
}
};
