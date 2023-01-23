<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setup(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * @dataProvider publicPageProvider
     */
    public function testAccessNonloginUser(string $page, int $code)
    {
        $response = $this->get($page);
        $response->assertStatus($code);
    }

    public function publicPageProvider(): array
    {
        return [
            ['/', 200],
            ['/welcome', 200],
            ['/member/home', 302],
            ['/user/profile', 302],
        ];
    }

    /**
     * @dataProvider memberPageProvider
     */
    public function testAccsessAuthUser(int|string ...$data)
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
        $response = $this->get($data[0]);
        $response->assertStatus($data[1]);
    }

    public function memberPageProvider(): array
    {
        return [
            ['/user/profile', 200],
            ['/member/home', 200],
        ];
    }
}
