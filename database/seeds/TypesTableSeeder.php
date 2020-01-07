<?php

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $currentTime = date('Y-m-d H:i:s');
        DB::table('types')->insert([
            ['tag'=>'XW', 'desc'=>'新闻', 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['tag'=>'HD', 'desc'=>'活动', 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['tag'=>'GL', 'desc'=>'攻略', 'created_at'=> $currentTime, 'updated_at'=> $currentTime]
        ]);
    }
}
