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
    Schema::table('articles', function (Blueprint $table) {
        $table->string('title')->nullable()->after('id');
        $table->longText('isi')->nullable()->after('slug');
        $table->string('gambar')->nullable()->after('isi');
        $table->boolean('is_published')->default(false)->after('gambar');
    });
}

public function down(): void
{
    Schema::table('articles', function (Blueprint $table) {
        $table->dropColumn(['title', 'isi', 'gambar', 'is_published']);
    });
}
};
