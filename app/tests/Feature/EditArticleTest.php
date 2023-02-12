<?php

namespace Tests\Feature;

use App\Models\User;
use Auth;
use Tests\TestCase;
use Database\Seeders\EditArticleTestSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditArticleTest extends TestCase
{
    use RefreshDatabase;
    private $table_name = 'articles';

    public function setup(): void
    {
        parent::setUp();
        $this->seed();
        $user = User::factory()->create();
        $this->seed(EditArticleTestSeeder::class);
        Auth::login($user);
    }

    // $this->artisan('migrate:fresh', ['--seed' => true]);
    // $this->assertTrue(true);

    /**
     * @test
    *  @dataProvider articleDataProvider
     *
     * @return void
     */
    public function 記事編集(array $postData)
    {
        $this->assertDatabaseHas($this->table_name, [
            'title' => 'sugoi',
            'content' => 'yabai',
            'author' => 1,
            'status_id' => 1,
        ]);

        $this->put(route('article.update', ['article' => 1]), $postData);
            //  ->assertRedirect(route('dashboard'));

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

    public function 記事編集失敗(array $postData =[]){
        $this->assertDatabaseMissing($this->table_name, [
            'title' => 'test_title',
        ]);
    }


    public function articleDataProvider(): array
    {
        return [
            "記事投稿" =>  [[
                'title' => 'test_title',
                'content' => 'test_content',
                'status_id' => '0',
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
    public function 記事編集がvalidationによって失敗する(array $err, array $postData)
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
