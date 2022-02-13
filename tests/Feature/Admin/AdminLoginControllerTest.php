<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\AdminUser;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLoginControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * setup
     *
     * @return void
     */
    protected function setup(): void
    {
        parent::setup();

        $this->adminUser = AdminUser::factory([
                'login_id' => 'valid-test',
                'is_active' => true,
            ])->create();
        $this->deletedAdminUser = AdminUser::factory([
                'login_id' => 'deleted-test',
                'is_active' => true,
                'deleted_at' => Carbon::now(),
            ])->create();
        $this->invalidAdminUser = AdminUser::factory([
                'login_id' => 'inactive-test',
                'is_active' => false,
            ])->create();
    }
    
    /**
     * test
     *
     * @return void
     */
    public function testログイン画面の表示()
    {
        $response = $this->get(route('admin.login'));

        $response->assertOk();
        // ログインしていないことを確認
        $this->assertGuest('admin');
    }
    
    /**
     * test
     *
     * @return void
     * @see Illuminate\Foundation\Auth\AuthenticatesUsers::logout()
     */
    public function testログアウト()
    {
        $response = $this->actingAs($this->adminUser, 'admin')
            ->post(route('admin.logout'));

        $response->assertRedirect(route('admin.show_login'));
        $this->assertGuest('admin');
    }

    /**
     * @param array $data
     * @dataProvider validParamProvider
     * @see App\Http\Middleware\RedirectIfAuthenticated
     */
    public function test有効なログイン(array $data)
    {
        $response = $this->post(route('admin.login'), $data);

        // ログイン後のリダイレクト先を確認
        $response->assertRedirect(route('admin.home'));
        // ログインできていることを確認
        $this->assertAuthenticated('admin');
    }

    /**
     * validParamProvider
     * @return array
     */
    public function validParamProvider(): array
    {
        return [
            [
                'data' => [
                    'login_id' => 'valid-test',
                    'password' => 'password',
                ],
            ],
        ];
    }

    /**
     * @param array $data
     * @dataProvider invalidParamProvider
     */
    public function test無効なログイン(array $data)
    {
        $response = $this->post(route('admin.login'), $data);

        // バリデーションエラーが発生していることを確認
        $response->assertInvalid();
        // ログインできていないことを確認
        $this->assertGuest('admin');
    }

    /**
     * seedで設定している、不正なユーザをセット
     * @return array
     */
    public function invalidParamProvider(): array
    {
        return [
            [
                'data' => [
                    'login_id' => 'deleted-test',
                    'password' => 'password',
                ],
                'data' => [
                    'login_id' => 'inactive-test',
                    'password' => 'password',
                ],
            ],
        ];
    }
}
