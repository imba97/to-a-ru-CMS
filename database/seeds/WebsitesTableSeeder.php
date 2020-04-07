<?php

use Illuminate\Database\Seeder;

class WebsitesTableSeeder extends Seeder
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
        DB::table('websites')->insert([
            [
                'gamename' => '网站1',
                'd_author' => '网站1运营组',
                'm_tag' => 'wap',
                'copyfile' => '',
                'created_at'=> $currentTime,
                'updated_at'=> $currentTime
            ],
            [
                'gamename' => '网站2',
                'd_author' => '网站2运营组',
                'm_tag' => 'wap',
                'copyfile' => '',
                'created_at'=> $currentTime,
                'updated_at'=> $currentTime
            ]
        ]);
    }
}
