<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MosqueControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_belum_login_tidak_dapat_mendaftar_masjid(): void
    {
        $response = $this->post(route('daftar.masjid.store'), []);

        $response->assertRedirect(route('login'));
    }

    public function test_nama_masjid_kosong_ditolak(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('daftar.masjid.store'), [
                'mosque_name' => '',
                'founded' => 2000,
                'capacity' => '100 jamaah',
                'address' => 'Jl. Contoh No. 1',
                'kelurahan' => 'Contoh',
                'kecamatan' => 'Contoh',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'phone' => '081234567890',
                'email' => 'masjid@example.com',
                'imam_name' => 'Ahmad',
                'chairman_name' => 'Abdul',
                'agree' => '1',
            ]);

        $response->assertSessionHasErrors('mosque_name');
    }
}
?>