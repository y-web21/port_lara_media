<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Database\Seeders\ArticleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if (App::isLocal('local')) {
            // 外部制約キー依存テーブルを先に削除
            ArticleSeeder::truncate();
        };

        $this->call([
            ArticleStatusesSeeder::class,
        ]);

        if (App::isLocal('local')) {
            $this->call([
                UserSeeder::class,
                ArticleSeeder::class,
            ]);
        };

    }
}
