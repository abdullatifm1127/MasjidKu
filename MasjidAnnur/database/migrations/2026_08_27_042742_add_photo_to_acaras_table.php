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
    if (!Schema::hasColumn('acaras', 'photo')) {
        Schema::table('acaras', function (Blueprint $table) {
            $table->string('photo')->nullable();
        });
    }
}

public function down(): void
{
    if (Schema::hasColumn('acaras', 'photo')) {
        Schema::table('acaras', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
}
};
