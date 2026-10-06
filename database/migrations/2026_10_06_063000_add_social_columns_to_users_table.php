<?php
// database/migrations/2026_10_06_100000_add_social_columns_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('provider')->nullable()->after('password');
            $t->string('provider_id')->nullable()->after('provider');
            $t->string('avatar')->nullable()->after('provider_id');
            $t->index(['provider', 'provider_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropIndex(['provider', 'provider_id']);
            $t->dropColumn(['provider', 'provider_id', 'avatar']);
        });
    }
};