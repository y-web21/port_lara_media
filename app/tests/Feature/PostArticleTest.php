<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PostArticleTest extends TestCase
{

    use RefreshDatabase;
    private $user;
    private $table_name = 'articles';

    public function setup(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

    /**
     * @test
     * @dataProvider articleDataProvider
     *
     * @return void
     */
    public function 記事投稿(array $postData)
    {
        $this->post(route('article.store'), $postData)
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas($this->table_name, [
            'title' => $postData['title'],
            'content' => $postData['content'],
            'status_id' => $postData['status_id'],
        ]);

        $postedRecord = Article::orderBy('id', 'desc')->first();
        $this->get(route('dashboard'))
            ->assertSee(__('Post has been completed.'));
        // 投稿内容がダッシュボードに反映されているか確認
        //     ->assertSee($postData['title'])
        //     ->assertSee($postData['content'])
        //     ->assertSee($postedRecord->status_name);

        $postedRecord->delete();
    }

    public function articleDataProvider(): array
    {
        return [
            [[
                'title' => 'test_title',
                'content' => 'test_content',
                'status_id' => '0',
            ]],
            [[
                'title' => 'test_title2',
                'content' => 'test_content2',
                'status_id' => '1',
            ]],
        ];
    }
}
