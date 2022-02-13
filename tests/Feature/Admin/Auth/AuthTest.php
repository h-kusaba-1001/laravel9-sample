<?php

namespace Tests\Feature\Admin\Auth;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider validAuthenticationProvider
     * @param array $data
     */
    public function testValidAuthentication(array $data)
    {
        $this->assertCredentials($data, 'admin');
    }

    /**
     * seedで設定しているユーザをセット
     * @return array
     */
    public function validAuthenticationProvider(): array
    {
        return [
            [
                'data' => [
                    'login_id' => 'valid',
                    'password' => 'password',
                ],
            ],
        ];
    }

    /**
     * @dataProvider invalidAuthenticationProvider
     * @param array $data
     */
    public function testInvalidAuthentication(array $data)
    {
        $this->assertInvalidCredentials($data, 'admin');
    }

    /**
     * seedで設定している削除されたユーザをセット
     * @return array
     */
    public function invalidAuthenticationProvider(): array
    {
        return [
            [
                'data' => [
                    'login_id' => 'inactive',
                    'password' => 'password',
                ],
                'data' => [
                    'login_id' => 'deleted',
                    'password' => 'password',
                ],
            ],
        ];
    }
}
