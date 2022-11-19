<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * @return void
     */
    public function test_home()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * test_basic_access
     *
     * @dataProvider getAccessDataProvider
     * @param  string $page
     * @return void
     */
    public function test_basic_access(string $page)
    {
        $response = $this->get($page);
        $response->assertStatus(200);
    }

    /**
     * getAccessDataProvider
     *
     * @return array
     */
    public function getAccessDataProvider(): array
    {
        return [
            ['/'],
            ['/welcome'],
        ];
    }
}
