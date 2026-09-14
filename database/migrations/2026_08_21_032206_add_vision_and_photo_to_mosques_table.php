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
        $table->text('about_vision')->nullable()->after('facilities');
        $table->string('about_photo', 255)->nullable()->after('about_vision');
    });
}

public function down()
{
    Schema::table('mosques', function (Blueprint $table) {
        $table->dropColumn(['about_vision', 'about_photo']);
    });
}
};
