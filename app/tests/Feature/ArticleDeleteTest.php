<?php

namespace Tests\Feature;

use Auth;
use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Exceptions\UrlGenerationException;

class ArticleDeleteTest extends TestCase
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
    public function 削除ページルーティングパラメータ未指定による失敗(): void
    {
        $this->expectException(UrlGenerationException::class);
        $this->delete(route('article.destroy'));
    }

    /** @test */
    public function 削除ページ無効パラメータ(): void
    {
        $this->delete(route('article.destroy', ['article' => 0]))
            ->assertStatus(404);
    }

    /** @test */
    public function 記事削除失敗_illegal_http_method(): void
    {
        // delete
        $this->get(route('article.destroy', ['article' => 1]))
            ->assertStatus(405);
    }

    /** @test */
    public function 記事編集失敗_DB要因の失敗_422(): void
    {
        $mock = Mockery::mock(Article::class)->makePartial();
        $mock->shouldReceive('deleteArticle')
            ->once()
            ->andReturn(false);
        $this->app->instance(Article::class, $mock);

        $this->delete(route('article.destroy', ['article' => $this->article->id]))
            ->assertStatus(422);
    }

    /** @test */
    public function 記事削除成功(): void
    {
        $this->assertDatabaseHas($this->table_name, [
            'id' => $this->article->id,
        ]);

        $this->delete(route('article.destroy', ['article' => $this->article->id]))
        ->assertStatus(302)
        ->assertRedirect(route('dashboard'));
        // soft delete
        $this->assertSoftDeleted($this->table_name, [
            'id' => $this->article->id,
        ]);
    }
}
