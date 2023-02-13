<?php

namespace Tests\Feature;

use Auth;
use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use Database\Seeders\EditArticleTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditArticleTest extends TestCase
{
    use RefreshDatabase;
    private $table_name = 'articles';

    public function setup(): void
    {
        parent::setUp();
        $this->seed();
        Article::query()->forceDelete();
        $user = User::factory()->create();
        $this->seed(EditArticleTestSeeder::class);
        Auth::login($user);
    }

    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function 記事編集成功(array $postData): void
    {

        $this->assertDatabaseHas($this->table_name, [
            'title' => 'sugoi',
            'content' => 'yabai',
            'author' => 1,
            'status_id' => 1,
        ]);

        $this->put(route('article.update', ['article' => 1]), $postData)
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas($this->table_name, [
            'title' => $postData['title'],
            'content' => $postData['content'],
            'status_id' => $postData['status_id'],
        ]);

        // $postedRecord = Article::orderBy('id', 'desc')->first();
        // $this->get(route('dashboard'))
        //     ->assertSee(__('Post has been completed.'));
        // // 投稿内容がダッシュボードに反映されているか確認
        //     ->assertSee($postData['title'])
        //     ->assertSee($postData['content'])
        //     ->assertSee($postedRecord->status_name);
    }

    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function 記事編集失敗_notExistsId(array $postData): void
    {
        $this->put(route('article.update', ['article' => 0]), $postData)
            ->assertStatus(404);
        $this->assertDatabaseMissing($this->table_name, [
            'title' => $postData['title'],
            'content' => $postData['content'],
            'status_id' => $postData['status_id'],
        ]);
    }

    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function 記事編集失敗_DB要因の失敗_422(array $postData): void
    {
        $mock = Mockery::mock(Article::class)->makePartial();
        $mock->shouldReceive('updateArticle')
            ->once()
            ->andReturn(false);
        $this->app->instance(Article::class, $mock);

        $this->put(route('article.update', ['article' => 1]), $postData)->assertStatus(422);
    }

    public function articleDataProvider(): array
    {
        return [
            "正常データ" =>  [[
                'title' => 'test_title',
                'content' => 'test_content',
                'status_id' => '0',
            ]],
        ];
    }

    /**
     * @test
     * @dataProvider articleValidateFaildDataProvider
     * @param array<string> $err
     * @param array{
     *   title: string,
     *   content: string,
     *   status_id: string|int
     * } $postData
     */
    public function 記事編集がvalidationによって失敗する(array $err, array $postData): void
    {
        $this->get(route('article.edit'), ['id' => '1']);
        $this->post(route('article.update'), $postData)
            ->assertRedirect(route('article.edit'))
            ->assertSessionHasErrors($err);

        $this->assertDatabaseMissing($this->table_name, [
            'title' => $postData['title'],
            'content' => $postData['content'],
            'status_id' => $postData['status_id'],
        ]);
    }

    /**
     * EDITでは、共通の validate のパターンの発火のみを確認してパターンはPost側に委ねる
     * @return array
     */
    public function articleValidateFaildDataProvider(): array
    {
        return [
            'title 文字数オーバー' =>
            [
                ['title'],
                [
                    'title' => sprintf("%0101s", 0),
                    'content' => sprintf("%05000s", 0),
                    'status_id' => '0',
                ]
            ],
        ];
    }
}
