<?php

namespace Tests\Feature;

use App\Models\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログインに成功すること
     */
    public function test_Login_ok(): void
    {
        $user = factory(Admin::class)->create();

        $response = $this->json('POST', route('auth.login'), [
            'name' => $user->name,
            'password' => 'password',
        ]);

        $response->assertOk();
        $this->assertAuthenticatedAs($user);
    }

    /**
     * ログインに失敗すること
     */
    public function test_Login_ng(): void
    {
        $response = $this->json('POST', route('auth.login'), [
            'name' => 'dummy',
            'password' => 'password',
        ]);

        $response->assertStatus(401)->assertJson(['error' => 'Unauthorized']);
    }

    /**
     * ログインしたらログアウトに成功すること
     */
    public function test_Logout_ok(): void
    {
        $user = factory(Admin::class)->create();

        $res_login = $this->json('POST', route('auth.login'), [
            'name' => $user->name,
            'password' => 'password',
        ]);

        $res_login->assertOk();
        $this->assertAuthenticatedAs($user);

        $res_logout = $this->json('POST', route('auth.logout'));

        $res_logout->assertOk()->assertJson(['status' => 'success']);
    }

    /**
     * ログアウトに失敗すること
     */
    public function test_Logout_ng(): void
    {
        $response = $this->json('POST', route('auth.logout'));

        $response->assertStatus(401)->assertJson(['error' => 'Unauthorized',]);
    }

    /**
     * ログイン後にリフレッシュに成功すること
     */
    public function test_reflesh_ok(): void
    {
        $user = factory(Admin::class)->create();

        $res_login = $this->json('POST', route('auth.login'), [
            'name' => $user->name,
            'password' => 'password',
        ]);

        $res_login->assertOk();
        $this->assertAuthenticatedAs($user);

        $res_refresh = $this->json('GET', route('auth.refresh'));

        $res_refresh->assertOk()->assertJson(['status' => 'successs']);
        $this->assertAuthenticatedAs($user);
    }

    /**
     * ログインしていなければリフレッシュに失敗すること
     */
    public function test_reflesh_ng(): void
    {
        $response = $this->json('GET', route('auth.refresh'));

        $response->assertStatus(401)->assertJson(['error' => 'refresh failed']);
    }
}
