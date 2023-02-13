<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EditArticleTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title' => 'sugoi',
                'content' => 'yabai',
                'author' => User::first()->id,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('articles')->insert($data);
    }

    public static function truncate()
    {
        $table = DB::table('articles');
        $table->truncate();
    }

}
