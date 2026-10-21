<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            ['key' => 'default', 'name' => 'Default Asli', 'description' => 'Tampilan asli bawaan sistem tanpa modifikasi warna khusus.', 'is_active' => true],
            ['key' => 'emerald', 'name' => 'Emerald Minimalist', 'description' => 'Nuansa hijau zamrud yang bersih dan segar.', 'is_active' => true],
            ['key' => 'terracotta', 'name' => 'Warm Terracotta', 'description' => 'Nuansa terakota hangat khas masjid tradisional.', 'is_active' => true],
            ['key' => 'corporate', 'name' => 'Corporate Navy', 'description' => 'Gaya korporat dark mode elegan.', 'is_active' => true],
        ];

        foreach ($themes as $theme) {
            Theme::updateOrCreate(['key' => $theme['key']], $theme);
        }
    }
}