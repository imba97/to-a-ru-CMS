<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->increments('id')->comment('网站ID');
            $table->string('gamename')->comment('网站名');
            $table->string('d_author')->comment('文章默认作者显示名');
            $table->string('m_tag')->comment('mobile_tag，手机版官网标签，用于目录名');
            $table->string('copyfile')->comment('需要直接复制的文件名');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('websites');
    }
}
