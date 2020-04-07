@extends('layouts.home')

@section('content')
    <table class="table table-hover">
        <caption>文章列表</caption>
        <thead>
            <tr>
                <th>官网ID</th>
                <th>官网名称</th>
                <th>默认发布者</th>
                <th>创建时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody id="websites">
            @foreach($websites as $website)
                <tr>
                    <td>{{$website->id}}</td>
                    <td>{{$website->gamename}}</td>
                    <td>{{$website->d_author}}</td>
                    <td>{{$website->created_at}}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('website.edit', $website->id) }}" target="_self" class="btn btn-default">编辑</a>
                            <div class="btn-group">
                                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    快速编辑
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);">设置1</a></li>
                                    <li><a href="#">设置2</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div id="page" class="pull-right">
        {{ $websites->render() }}
    </div>
@stop()

@section('footercode')
<script>
$(function() {

});
</script>
@stop()