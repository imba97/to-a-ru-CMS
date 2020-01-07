<?php

namespace App\Http\Controllers\Home;

use App\Type;
use App\Website;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebSiteController extends Controller
{
    //
    private $_paginate;

    public function __construct() {

        $this->_paginate = 10;

        $this->middleware('auth');
    }

    public function index() {

        $websites = Website::orderBy('created_at', 'desc')->paginate($this->_paginate);

        return view('Home.Website.index', [
            'websites'  =>  $websites
        ]);
    }

    public function create() {
        return view('Home.WebSite.edit', [
            'edittype'      =>  'add'
        ]);
    }

    public function add(Request $request) {
        $data = $request->input('WebSite');

        $webSite = new Website;
        $webSite->gamename = $data['gamename'];
        $webSite->d_author = $data['d_author'];
        $webSite->m_tag = $data['m_tag'];
        $webSite->copyfile = $data['copyfile'];

        $result = array(
            'status'    => 0,
            'info'  => '添加失败'
        );

        if($webSite->save()) {
            $result['status'] = 1;
            $result['data'] = array(
                'id'    =>  $webSite->id
            );
            $result['info'] = '添加成功';
        }

        return response()->json($result);
    }

    public function show($id) {
        $webSite = WebSite::find($id);
        if(!$webSite) {
            return redirect('/site')->with('message', '该站点不存在');
        }
        return view('Home.WebSite.show', [
            'webSite'   =>  $webSite
        ]);
    }

    // 根据网站ID获取游戏名
    public function getGameNamesByWebSiteIDs(Request $request) {
        $wsids = $request->get('wsids');
        $wsidArr = explode(',', $wsids);

        $webSiteInfo = Website::getGameNamesByWsids($wsidArr);
        $resData = array();

        foreach($webSiteInfo as $index => $item) {
            $resData[$item['id']] = $item['gamename'];
        }

        return response()->json($resData);
    }

}
