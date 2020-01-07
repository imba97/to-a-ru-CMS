<?php

namespace App\Http\Controllers\Home;

use App\Website;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $webSites = WebSite::all();

        return view('Home.User.index', [
            'webSites'   =>  $webSites
        ]);
    }
}
