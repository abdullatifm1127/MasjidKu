<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $row = [
            'method_name' => 'QRIS',
            'is_active'   => 0,
            'config_data' => json_encode(['merchant_name' => '', 'qris_image' => '']),
        ];
        if (Schema::hasColumn('payment_settings', 'created_at')) $row['created_at'] = now();
        if (Schema::hasColumn('payment_settings', 'updated_at')) $row['updated_at'] = now();

        if (!DB::table('payment_settings')->where('code', 'qris')->exists()) {
            DB::table('payment_settings')->insert($row + ['code' => 'qris']);
        }
    }

    public function down(): void
    {
        DB::table('payment_settings')->where('code', 'qris')->delete();
    }
};
