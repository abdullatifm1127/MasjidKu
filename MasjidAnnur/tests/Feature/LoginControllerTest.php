<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public function test_halaman_login_dapat_dibuka(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_login_dengan_data_kosong_gagal(): void
    {
        $response = $this->post(route('login'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'email',
            'password',
        ]);
    }
}