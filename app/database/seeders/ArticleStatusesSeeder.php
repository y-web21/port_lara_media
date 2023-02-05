<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleStatusesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = DB::table('article_statuses');
        $statuses = ['非公開', '公開'];
        foreach($statuses as $key => $status){
            $table->insert(['status_id' => $key ,'status_name' => $status]);
        }
    }
}
