<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::factory(10)->create();
    }

    public static function truncate()
    {
        $table = DB::table('articles');
        $table->truncate();
    }

}
