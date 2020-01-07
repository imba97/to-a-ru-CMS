<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
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
        DB::table('articles')->insert([
            ['title'=>'默认文章1', 'content'=>'默认文章1内容', 'wsid'=>1, 'author'=>'', 'type'=>1, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章2', 'content'=>'默认文章2内容', 'wsid'=>1, 'author'=>'', 'type'=>1, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章3', 'content'=>'默认文章3内容', 'wsid'=>1, 'author'=>'', 'type'=>1, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章4', 'content'=>'默认文章4内容', 'wsid'=>1, 'author'=>'', 'type'=>1, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章5', 'content'=>'默认文章5内容', 'wsid'=>1, 'author'=>'', 'type'=>1, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章6', 'content'=>'默认文章6内容', 'wsid'=>1, 'author'=>'', 'type'=>1, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章7', 'content'=>'默认文章7内容', 'wsid'=>1, 'author'=>'', 'type'=>1, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章8', 'content'=>'默认文章8内容', 'wsid'=>1, 'author'=>'', 'type'=>2, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章9', 'content'=>'默认文章9内容', 'wsid'=>1, 'author'=>'', 'type'=>2, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章10', 'content'=>'默认文章10内容', 'wsid'=>1, 'author'=>'', 'type'=>2, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章11', 'content'=>'默认文章11内容', 'wsid'=>1, 'author'=>'', 'type'=>2, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章12', 'content'=>'默认文章12内容', 'wsid'=>1, 'author'=>'', 'type'=>3, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章13', 'content'=>'默认文章13内容', 'wsid'=>1, 'author'=>'', 'type'=>3, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章14', 'content'=>'默认文章14内容', 'wsid'=>1, 'author'=>'', 'type'=>3, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章15', 'content'=>'默认文章15内容', 'wsid'=>1, 'author'=>'', 'type'=>3, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章16', 'content'=>'默认文章16内容', 'wsid'=>1, 'author'=>'', 'type'=>1, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章17', 'content'=>'默认文章17内容', 'wsid'=>1, 'author'=>'', 'type'=>2, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章18', 'content'=>'默认文章18内容', 'wsid'=>2, 'author'=>'', 'type'=>3, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章19', 'content'=>'默认文章19内容', 'wsid'=>2, 'author'=>'', 'type'=>1, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
            ['title'=>'默认文章20', 'content'=>'默认文章20内容', 'wsid'=>2, 'author'=>'', 'type'=>2, 'status' =>1, 'created_at'=> $currentTime, 'updated_at'=> $currentTime],
        ]);
    }
}
