<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('mosques', function (Blueprint $table) {
        $table->string('about_photo_secondary')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('mosques', function (Blueprint $table) {
        $table->dropColumn('about_photo_secondary');
    });
}
};
