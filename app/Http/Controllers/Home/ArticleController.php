<?php

namespace App\Http\Controllers\Home;

use App\Article;
use App\Type;
use App\Website;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    //
    // 每页显示的文章数
    private $_paginate;

    function __construct()
    {
        $this->_paginate = 10;
        $this->middleware('auth')->except('show');
    }

    public function index() {
        $articles = Article::orderBy('created_at', 'desc')->paginate($this->_paginate);
        return view('Home.Article.index', [
            'articles'  =>  $articles
        ]);
    }

    public function create() {

        $gamelist = Website::all();
        $typelist = Type::all();

        return view('Home.Article.edit', [
            'edittype'      =>  'add',
            'gamelist'  =>  $gamelist,
            'typelist'  =>  $typelist
        ]);
    }

    public function add(Request $request) {
        $data = $request->input('Article');

        $article = new Article;
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->wsid = $data['wsid'];
        $article->type = $data['type'];
        $article->status = 1;

        $result = array(
            'status'    => 0,
            'info'  => '添加失败'
        );

        if($article->save()) {
            $result['status'] = 1;
            $result['data'] = array(
                'id'    =>  $article->id
            );
            $result['info'] = '添加成功';
        }

        return response()->json($result);
    }

    public function edit($id) {
        $article = Article::leftJoin('websites', 'articles.wsid', '=', 'websites.id')
            ->select('articles.*', 'websites.gamename', 'websites.d_author')
            ->find($id);

        $gamelist = Website::all();
        $typelist = Type::all();

        if(!$article) {
            return redirect('/article')->with('message', '该文章不存在');
        }

        return view('Home.Article.edit', [
            'type'      =>  'edit',
            'article'   =>  $article,
            'gamelist'  =>  $gamelist,
            'typelist'  =>  $typelist
        ]);
    }

    public function update(Request $request, $id) {
        $data = $request->input('Article');

        $article = Article::find($id);
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->wsid = $data['wsid'];
        $article->type = $data['type'];
        $article->created_at = $data['created_at'];

        $result = array(
            'status'    => 0,
            'info'  => '修改失败'
        );

        if($article->save()) {
            $result['status'] = 1;
            $result['data'] = array(
                'id' => $article->id
            );
            $result['info'] = '修改成功';

            // 重新生成文件
            $builder = new BuildController;
            // 把要重新生成的文章ID添加到wipeDist
            array_push($builder->wipeDist, $article->id);
            // 生成
            $builder->runArticle($article->wsid, $article->id);

        }

        return response()->json($result);
    }

    public function show($id) {
        $article = Article::find($id);
        if(!$article) {
            return redirect('/article')->with('message', '该文章不存在');
        }
        return view('Home.Article.show', [
            'article'   =>  $article
        ]);
    }


    public function changeArticleStatus(Request $request) {
        $id = $request->get('id');
        $type = $request->get('type');
        $article = Article::find($id);

        switch($type) {
            case 'status':
                $article['status'] = $article['status'] == 1 ? 0 : 1;
                $statusClass = getArticleStatusClass($article['status']);
                $statusText = getArticleStatusText($article['status']);
                break;
            case 'istop':
                $article['istop'] = $article['istop'] == 1 ? 0 : 1;
                $statusClass = getArticleStatusClass($article['istop']);
                $statusText = getArticleIsTopText($article['istop']);
                break;
        }

        $result = array(
            'status'    => 0,
            'info'  => '修改失败'
        );

        if($article->save()) {

            $result['status'] = 1;
            $result['data'] = array(
                'id'            => $article['id'],
                'type'          => $type,
                'statusClass'   => $statusClass,
                'statusText'    => $statusText
            );
            $result['info'] = '修改成功';
        }

        return response()->json($result);
    }

    public function delete() {

        $id = request()->get('id');

        $article = Article::find($id);
        $result = array(
            'status'    => 0,
            'info'  => '删除失败'
        );

        if($article->delete()) {

            $page = request()->get('page');

            $getArticle = new Article;
            $new_article = $getArticle->offset($this->_paginate * ($page - 1))->limit($this->_paginate)->get();
            $maxPage = ceil($getArticle->count() / $this->_paginate);

            // 状态对应的文字和class、默认作者
            foreach($new_article as $key => $art) {
                $new_article[$key]['statusClass'] = getArticleStatusClass($art['status']);
                $new_article[$key]['statusText'] = getArticleStatusText($art['status']);

                $new_article[$key]['isTopClass'] = getArticleIsTopClass($art['istop']);
                $new_article[$key]['isTopText'] = getArticleIsTopText($art['istop']);

                if($new_article[$key]['author'] === '') {
                    $new_article[$key]['author'] = Website::getDefaultAuthorByWsid($art['wsid']);
                }
            }

            $result['status'] = 1;
            $result['data'] = array(
                'article'   =>  $new_article,
                'maxPage'      =>  $maxPage
            );
            $result['info'] = '删除成功';
        }

        return response()->json($result);
    }
}
