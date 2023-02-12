<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Artisan;
use Auth;
use Database\Seeders\ArticleSeeder;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\EditArticleTestSeeder;

class EditArticleTest extends TestCase
{
    use RefreshDatabase;
    private $table_name = 'articles';

    public function setup(): void
    {
        parent::setUp();
        $this->seed();
        $user = User::factory()->create();
        Auth::login($user);
        $this->seed(EditArticleTestSeeder::class);
    }

    /**
     * @test
    *  @ddataProvider articleDataProvider
     *
     * @return void
     */
    public function 記事編集(array $postData =[])
    {
        $this->assertDatabaseHas($this->table_name, [
            'title' => 'sugoi',
            'content' => 'yabai',
            'author' => 1,
            'status_id' => 1,
        ]);

        $this->put(route('article.update', ['id' => '1']), $postData)
             ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas($this->table_name, [
            'title' => 'test_title',
        ]);

        // $postedRecord = Article::orderBy('id', 'desc')->first();
        // $this->get(route('dashboard'))
        //     ->assertSee(__('Post has been completed.'));
        // // 投稿内容がダッシュボードに反映されているか確認
        //     ->assertSee($postData['title'])
        //     ->assertSee($postData['content'])
        //     ->assertSee($postedRecord->status_name);

        // $postedRecord->delete();
    }

    public function articleDataProvider(): array
    {
        return [
            "記事投稿" =>  [[
                'title' => 'test_title',
                'content' => 'test_content',
                'status_id' => '0',
            ]],
            "記事投稿2" =>  [[
                'title' => 'test_title2',
                'content' => 'test_content2',
                'status_id' => '1',
            ]],
        ];
    }

    /**
     * @te
     * @dataProvider articleValidateFaildDataProvider
     * @param array<string> $err
     * @param array{
     *   title: string,
     *   content: string,
     *   status_id: string|int
     * } $postData
     * @return void
     */
    public function 記事投稿がvalidationによって失敗する(array $err, array $postData)
    {
        $this->get(route('article.create'));
        $this->post(route('article.store'), $postData)
            ->assertRedirect(route('article.create'))
            ->assertSessionHasErrors($err);

        $this->assertDatabaseMissing($this->table_name, [
            'title' => $postData['title'],
            'content' => $postData['content'],
            'status_id' => $postData['status_id'],
        ]);
    }

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
            'content 文字数オーバー' =>
            [
                ['content'],
                [
                    'title' => sprintf("%0100s", 0),
                    'content' => sprintf("%05001s", 0),
                    'status_id' => '1',
                ]
            ],
        ];
    }
}
