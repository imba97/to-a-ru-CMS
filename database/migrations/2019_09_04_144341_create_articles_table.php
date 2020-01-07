<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id')->comment('文章ID');
            $table->string('title')->comment('标题');
            $table->text('content')->comment('内容');
            $table->integer('wsid')->comment('从属网站ID');
            $table->integer('istop')->default(0)->comment('是否置顶');
            $table->string('author')->default('')->comment('作者 空代表网站默认作者');
            $table->integer('status')->default(0)->comment('文章状态');
            $table->integer('type')->comment('文章类型 新闻/活动/等');
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
        Schema::dropIfExists('articles');
    }
}
