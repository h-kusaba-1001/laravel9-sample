<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
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

        $this->customer = Customer::factory([
                'email' => 'customer-test@example.com',
            ])->create();
        $this->deletedCustomer = Customer::factory([
                'email' => 'deleted-test@example.com',
                'deleted_at' => Carbon::now(),
            ])->create();
    }
    
    /**
     * test
     *
     * @return void
     */
    public function testログイン画面の表示()
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        // ログインしていないことを確認
        $this->assertGuest();
    }
    
    /**
     * test
     *
     * @return void
     * @see Illuminate\Foundation\Auth\AuthenticatesUsers::logout()
     */
    public function testログアウト()
    {
        // setup()で指定した有効なcustomerを指定
        $response = $this->actingAs($this->customer)
            ->post(route('logout'));

        // logoutメソッドのリダイレクト先を指定
        $response->assertRedirect('/');
        // ログインしていないことを確認
        $this->assertGuest();
    }

    /**
     * @param array $data
     * @dataProvider validParamProvider
     * @see App\Http\Middleware\RedirectIfAuthenticated
     */
    public function test有効なログイン(array $data)
    {
        $response = $this->post(route('login'), $data);

        // ログイン後のリダイレクト先を確認
        $response->assertRedirect(RouteServiceProvider::HOME);
        // ログインできていることを確認
        $this->assertAuthenticated();
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
                    'email' => 'customer-test@example.com',
                    // factoryで指定しているpasswordを指定
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
        $response = $this->post(route('login'), $data);

        // バリデーションエラーが発生していることを確認
        $response->assertInvalid();
        // ログインできていないことを確認
        $this->assertGuest();
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
                    'email' => 'deleted-test@example.com',
                    'password' => 'password',
                ],
            ],
        ];
    }
}
