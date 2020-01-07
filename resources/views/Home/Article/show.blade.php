<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head lang="en">
    <meta charset="UTF-8" http-equiv="Content-Type" content="text/html;">
    <title></title>
    <meta name="description" content="" />
    <meta name="Keywords" content="" />
    <meta name="renderer" content="webkit" />
    <link type="image/x-icon" href="https://static.86joy.com/game/ztx/images/favicon.ico" rel="bookmark">
    <link type="image/x-icon" href="https://static.86joy.com/game/ztx/images/favicon.ico" rel="icon">
    <link type="image/x-icon" href="https://static.86joy.com/game/ztx/images/favicon.ico" rel="shortcut icon">
    <link href="https://static.86joy.com/game/ztx/css/common.css" rel="stylesheet" type="text/css" />
    <link href="https://static.86joy.com/game/ztx/css/newspage.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript" src="https://static.86joy.com/game/ztx/js/jquery-1.9.1.min.js"></script>
    <script language="javascript" src="https://static.86joy.com/game/ztx/js/index.js"></script>
    <script type="text/javascript">
        $(function () {
            $(".tab li").on("click", function () {
                var id = $(this).attr("id");
                $(".tab li").removeClass("active");
                $(".tab_con ul").hide();
                $("#" + id).addClass("active");
                $("#card_con_" + id.substr(5)).show();
            });
        });
    </script>
    <script type="text/javascript">
        $(function () {
            $(".data li").on("click", function () {
                var id = $(this).attr("id");
                $(".data_con ul").hide();
                $(".data li").removeClass("active");
                $("#" + id).addClass("active");
                $("#data_con_" + id.substr(5)).show();
            });
        });
    </script>

    <script type="text/javascript">
        $(function () {
            $('#FontScroll').FontScroll({ time: 3000, num: 1 });
        });
    </script>
	
	<style>
        body {
            background: none;
        }
    </style>

</head>
<body>
<div class="main width">
    <div class="content fr">
        <ul class="tab">
            <!--<p style="padding: 10px 0 0 20px;">您现在的位置：首页>新闻中心</p>-->
        </ul>
        <div style="width: 891px;margin-left: 140px;margin-top: 16px;border: 3px solid #000;min-height: 500px;">
            <div class="newh"><h2 style="font-size: 20px; color: #483b3b;line-height:45px;">{{ $article->title }}</h2></div>
            <div class="newc">
                <div style="padding:30px">
                    {!! $article->content !!}
                    <p style="text-align:right;">{{ $article->created_at }}</p>
                </div>
            </div>
            <!--<div style="text-align:right;    bottom: 0;position: absolute;right: 0;">}</div>-->
        </div>
    </div>
</div>
</body>
</html>