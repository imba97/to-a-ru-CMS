<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'username'  =>  'imba97',
            'password'  =>  bcrypt('111111'),
            'api_token' =>  str_random(60)
        ]);
    }
}
