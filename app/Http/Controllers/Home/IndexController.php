<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
      $arr = DB::table('articles')->get();
      return view('Home.index', [
        'isPages'  =>  true
      ]);
    }

    public function error() {
        return view('common.error');
    }
}
