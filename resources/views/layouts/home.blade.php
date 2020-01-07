<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Auth::check() ? 'Bearer ' . Auth::user()->api_token : 'Bearer ' }}">

    <meta http-equiv="pragram" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="expires" content="0">

    <title>とあるCMS</title>

    @section('headercode')
    @show()

    {{-- <!-- Fonts -->

     <link href="https://fonts.googleapis.com/css?family=Raleway:300" rel="stylesheet" type="text/css">--}}

    <link href="{{ mix('css/app.css') }}" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #000000;
            font-family: 'Raleway', sans-serif;
            font-weight: 300;
            height: 100vh;
            margin: 0;
        }
        body {
            padding-top: 20px;
        }
    </style>
</head>
<body>
<div id="app" class="container">

    <div class="col-lg-12">
        @guest
            <div style="margin-top: 20px" class="alert alert-info text-center">请先登录。</div>
        @else
        @section('header')

            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                            <span class="sr-only">切换导航</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">とあるCMS</a>
                    </div>
                    <div class="collapse navbar-collapse" id="example-navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="{{ Request::getPathInfo() == '/' ? 'active' : '' }}"><a href="/">首页</a></li>
                            <li class="{{ Request::getPathInfo() == '/article' ? 'active' : '' }}"><a href="{{ url('article') }}">文章</a></li>
                            <li class="{{ Request::getPathInfo() == '/site' ? 'active' : '' }}"><a href="{{ url('site') }}">站点</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    快速生成 <b class="caret"></b>
                                </a>
                                <ul id="runBtnList" class="dropdown-menu">
                                    <li class="divider"></li>
                                </ul>
                            </li>
                        </ul>
                        <form class="navbar-form navbar-right" role="search">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search">
                            </div>
                            <button type="submit" class="btn btn-default">搜索文章</button>
                        </form>
                        <form action="{{ route('logout', [Auth::user()]) }}" method="post" class="navbar-form navbar-right" role="user">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <a href="{{ url('/user') }}" class="col-md-4">{{ Auth::user()->username }}</a>
                            </div>
                            <button type="submit" class="btn btn-danger">退出 <span class="oi oi-account-logout"></span></button>
                        </form>
                    </div>
                </div>
            </nav>

        @show()
        @endguest
    </div>

    <div class="col-lg-12">

        {{-- 重要警告弹窗 --}}
        @include('common.alert')

        {{-- 非重要警告弹窗 --}}
        @if(Session::has('message'))
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>{{ Session::get('message') }}</strong>
            </div>
        @endif

        @yield('content', '主内容区域')
    </div>

    <div class="col-lg-12">
        @section('footer')
        @show()
    </div>
</div>

<script src="{{ mix('js/app.js') }}"></script>
@section('footercode')

@show()
<script>

$(function(){

    initConfigHomeNav();

    $('#runAll').click(function() {
        $.ajax({
            type: 'POST',
            url: '{{ url('/api/build/all') }}',
            data: {
                'wsid': 1
            },
            success: function(json) {

            }
        });
    });

    $('#runIndex').click(function() {
        $.ajax({
            type: 'POST',
            url: '{{ url('/api/build/all') }}',
            data: {
                'wsid': 2,
                'runType': 'index'
            },
            success: function(json) {
                console.log(json);
            }
        });
    });

    @if(Session::has('alert'))
        $('#alertMessage').modal();
    @endif
});

function initConfigHomeNav() {

    var UserConfig = JSON.parse(window.localStorage.getItem('UserConfig'));

    if(UserConfig.webSite.length > 0) {
        var wsids = UserConfig.webSite.join(',');

        $.ajax({
            type: 'POST',
            url: '{{ url('/api/website/getGameNamesByWebSiteIDs') }}',
            data: {
                'wsids': wsids
            },
            success: function(json) {

                var html = '';

                for(var index in json) {
                    html += '<li><a href="javascript:runBuild(' + index + ');">' + json[index] + '</a></li>';
                }
                $('#runBtnList').html(html);
            }
        });
    }

    if(UserConfig.webSite !== undefined) {

    }
}

function runBuild(wsid) {
    $.ajax({
        type: 'POST',
        url: '{{ url('/api/build/all') }}',
        data: {
            'wsid': wsid
        },
        success: function(json) {
            if(typeof json['wsid'] !== 'undefined') {
                alert('成功！');
            }
        }
    });
}

</script>
</body>
</html>
