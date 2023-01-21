<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        if (!Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_screen_cannot_be_rendered_if_support_is_disabled()
    {
        if (Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_new_users_can_register()
    {
        if (!Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password-0',
            'password_confirmation' => 'Password-0',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * @test
     * @dataProvider invalid_register_login_password_provider
     */
    public function パスワード登録失敗ルールの検証(string $password, string $confirmPass=null)
    {
        if (!Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $password,
            'password_confirmation' => $confirmPass ?? $password,
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);
        $this->assertFalse($this->isAuthenticated(), 'User creation successed...');
    }

    public function invalid_register_login_password_provider()
    {
        return [
            'lowercase' => ['password'],
            'uppercase' => ['PASSWORD'],
            'number' => ['01234567'],
            'symbol' => ['-_@#-_@#'],
            'withoutSymbol' => ['PassWord'],
            'char7' => ['@7Passw'],
            'different' => ['Password-0', 'Password_0'],
        ];
    }
}
