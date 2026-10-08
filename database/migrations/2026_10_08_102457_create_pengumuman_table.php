<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mosque_id')->index();
            $table->string('title');
            $table->text('content');
            $table->string('category', 30)->default('umum');
            $table->string('image_path')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->string('status', 10)->default('terbit'); // draft | terbit
            $table->date('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};