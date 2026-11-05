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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Informasi Platform
            $table->string('platform_name')->default('MasjidKu');
            $table->string('support_email')->nullable();
            $table->string('whatsapp')->nullable();

            // Batas Tenant
            $table->unsignedInteger('max_mosque_free')->default(1);
            $table->unsignedInteger('max_announcement')->default(10);
            $table->unsignedInteger('max_event')->default(5);

            // Notifikasi Sistem
            $table->boolean('notif_registration')->default(true);
            $table->boolean('notif_donation')->default(true);
            $table->boolean('maintenance_mode')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};