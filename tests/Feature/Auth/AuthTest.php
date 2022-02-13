<?php

namespace Tests\Feature\Auth\AdminUser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * AuthTest
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * seed
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * @dataProvider validAuthenticationProvider
     * @param array $data
     */
    public function testValidAuthentication(array $data)
    {
        $this->assertCredentials($data);
    }

    /**
     * seedで設定しているユーザをセット
     * @see database/seeders/Develop/DebugAdminUserSeeder.php
     * @return array
     */
    public function validAuthenticationProvider(): array
    {
        return [
            [
                'data' => [
                    'email' => 'customer@example.com',
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
        $this->assertInvalidCredentials($data);
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
                    'email' => 'deleted@example.com',
                    'password' => 'password',
                ],
            ],
        ];
    }
}