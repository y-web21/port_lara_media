<?php

namespace Tests\Feature;

use Auth;
use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Routing\Exceptions\UrlGenerationException;

class EditArticleTest extends TestCase
{
    // # NOTE RefreshDatabase では AUTO_INCREMENT の採番はリセットされない
    use RefreshDatabase;
    private $table_name = 'articles';
    private $user;
    private $article;

    public function setup(): void
    {
        parent::setUp();

        $this->seed();
        $this->user = User::factory()->create();
        Auth::login($this->user);
        $this->article = Article::factory()->author($this->user->id)->create();
    }

    /** @test */
    public function 編集ページルーティングパラメータなし(): void
    {
        $this->expectException(UrlGenerationException::class);
        $this->get(route('article.edit'));
    }

    /** @test */
    public function 編集ページ無効パラメータ(): void
    {
        $this->get(route('article.edit', ['article' => 0]))
            ->assertStatus(404);
    }

    /** @test */
    public function 編集ページ正常アクセス(): void
    {
        $this->get(route('article.edit', ['article' => $this->article->id]))
            ->assertStatus(200);
    }

    /**
     * @test
     * @dataProvider articleDataProvider
     */
    public function 記事編集成功(array $postData): void
    {
        $this->assertDatabaseHas($this->table_name, [
            'title' => $this->article->title,
            'content' => $this->article->content,
            'author' => $this->user->id,
            'status_id' => $this->article->status_id,
        ]);

        $this->put(route('article.update', ['article' => $this->article->id]), $postData)
            ->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas($this->table_name, [
            'title' => $postData['title'],
            'content' => $postData['content'],
            'author' => $this->user->id,
            'status_id' => $postData['status_id'],
        ]);
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
    public function 記事編集失敗_illegal_method(array $postData): void
    {
        // put
        $this->post(route('article.update', ['article' => 0]), $postData)
            ->assertStatus(405);
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

        $this->put(route('article.update', ['article' => $this->article->id]), $postData)
            ->assertStatus(422);
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
        $this->get(route('article.edit', ['article' => $this->article->id]))->assertStatus(200);
        $this->put(route('article.update', ['article' => $this->article->id]), $postData)
            ->assertSessionHasErrors($err)
            ->assertRedirect(route('article.edit', ['article' => $this->article->id]));

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
