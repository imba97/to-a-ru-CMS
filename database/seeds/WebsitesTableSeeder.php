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
        DB::table('websites')->insert([
            ['gamename'=>'勇者编年史', 'd_author'=>'勇者编年史运营组'],
            ['gamename'=>'网站2', 'd_author'=>'网站2运营组']
        ]);
    }
}
