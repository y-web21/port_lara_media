<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->state([
            'name' => 'root',
            'password' => 'root'
        ])->create();
        User::factory(10)->unsafePass()->randVerifiedDate(365)->create();
        User::factory(2)->unsafePass()->unverified()->create();
    }
}
