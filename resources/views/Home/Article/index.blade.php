@extends('layouts.home')

@section('content')

    <table class="table table-hover">
        <caption>文章列表</caption>
        <thead>
            <tr>
                <th>文章ID</th>
                <th>标题</th>
                <th>状态</th>
                <th>所属游戏</th>
                <th>发布时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody id="articles">
            @foreach($articles as $article)
                <tr class="{{ getArticleStatusClass($article->status) }}" data-id="{{ $article->id }}">
                    <td>{{ $article->id }}</td>
                    <td>{{ $article->title }}</td>
                    <td><a class="status-btn" data-type="status" href="javascript:void(0);">{{ getArticleStatusText($article->status) }}</a></td>
                    <td>{{ getWebsiteByWsid($article->wsid) }}</td>
                    <td>{{ $article->created_at }}</td>
                    <td width="300">
                    <div class="btn-group">
                        <a href="{{ route('article.show', $article->id) }}" target="_blank" class="btn btn-default">查看</a>
                        <a href="{{ route('article.edit', $article->id) }}" target="_blank" class="btn btn-default">编辑</a>
                        <div class="btn-group">
                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            快速编辑
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="istop-btn {{ getArticleStatusClass($article->istop) }}" data-type="istop" href="javascript:void(0);">{{ getArticleIsTopText($article->istop) }}</a></li>
                            <li><a href="#">设置2</a></li>
                        </ul>
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger delete-btn" data-id="{{ $article->id }}">删除</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>

        <div class="btn-group">
            <a href="{{ url('article/create') }}" class="btn btn-success">新建</a>
        </div>

        <div id="page" class="pull-right">
            {{ $articles->render() }}
        </div>
@stop()

@section('footercode')
    <script>
        $('#articles').on('click', '.status-btn,.istop-btn', function() {
            if($(this).attr('data-getting') === 'true') return false;
            $(this).attr('data-getting', 'true');
            var data = {
                'id': $(this).parents('tr').attr('data-id'),
                'type': $(this).attr('data-type')
            };
            $.ajax({
                type: 'POST',
                url: '{{ url('/api/article/changeArticleStatus') }}',
                data: data,
                success: function(json) {
                    if(!json || json.status !== 1) {
                        $('#alertMessage').modal();
                        return false;
                    }

                    if(json.data.type === 'status') {
                        $('tr[data-id=' + json.data.id + ']')
                            .removeClass('active success warning danger')
                            .addClass(json.data.statusClass)
                            .find('.status-btn').text(json.data.statusText).removeAttr('data-getting')
                    } else if(json.data.type === 'istop') {
                        $('tr[data-id=' + json.data.id + ']').find('.istop-btn')
                            .removeClass('active success warning danger')
                            .addClass(json.data.statusClass)
                            .text(json.data.statusText)
                            .removeAttr('data-getting')
                    }

                },
                error: function() {
                    alert('修改失败');
                }
            });
        });

        $('#articles').on('click', '.delete-btn', function() {
            $(this).attr('disabled', 'disabled');

            var page = Math.floor($('#page ul li.active').text()) || 1;
            if($('#articles tr').length === 1 && page !== 1) {
                --page;
            }

            var data = {
                'id': $(this).parents('tr').attr('data-id'),
                'page': page
            };
            $.ajax({
                type: 'POST',
                url: '{{ url('/api/article/delete') }}',
                data: data,
                success: function(json) {
                    if(!json || json.status !== 1) {
                        $('#alertMessage').modal();
                        return;
                    }

                    if(json.data && typeof(json.data) === 'object') {
                        var html = '';
                        json.data.article.forEach(function(item, index) {
                            html +=
                                '<tr class="' + item.statusClass + '" data-id="' + item.id + '">' +
                                    '<td>' + item.id + '</td>' +
                                    '<td>' + item.title + '</td>' +
                                    '<td><a class="status-btn" data-type="status" href="javascript:void(0);">' + item.statusText + '</a></td>' +
                                    '<td>' + item.created_at + '</td>' +
                                    '<td width="300">' +
                                        '<div class="btn-group">' +
                                            '<a href="{{ url('/article/show') }}/' + item.id + '" target="_blank" class="btn btn-default">查看</a>' +
                                            '<a href="{{ url('/article/edit') }}/' + item.id + '" target="_blank" class="btn btn-default">编辑</a>' +
                                            '<div class="btn-group">' +
                                                '<a class="btn btn-default dropdown-toggle" data-toggle="dropdown">快速编辑<span class="caret"></span></a>' +
                                                '<ul class="dropdown-menu">' +
                                                '<li><a class="istop-btn ' + item.isTopClass + '" data-type="istop" href="javascript:void(0);">' + item.isTopText + '</a></li>' +
                                                '<li><a href="#">设置2</a></li>' +
                                                '</ul>' +
                                            '</div>' +
                                        '</div>' +
                                        '<button type="button" class="btn btn-danger delete-btn" data-id="' + item.id + '">删除</button>' +
                                    '</td>' +
                                '</tr>';
                        });
                        $('#articles').html(html);

                        // 获取当前页数，-2是去掉上一页下一页按钮
                        var pageNum = $('#page ul li').length - 2;

                        var currentPage = $('#page ul li.active').index();

                        // 只有一页删除分页按钮
                        if(json.data.maxPage === 1) {
                            if($('#page').length > 0) $('#page').remove();
                            return;
                        }

                        // 如果 当前总页数 - 1 等于 删除数据后查询的页数 说明少了一页，需要把最后一页的按钮删掉
                        if(pageNum - 1 === json.data.maxPage) {

                            $('#page ul li:eq(' + pageNum + ')').remove();

                            // 如果当前页数 - 最大页数 = 1 说明刚才删的按钮是被选中的，此时会没有按钮是选中状态，因此添加最后一个按钮为选中状态
                            if(currentPage - json.data.maxPage === 1) {
                                $('#page ul li:eq(' + (pageNum - 1) + ')').addClass('active');
                            }

                        }

                        // 如果 当前总页数 等于 选中的页数 就把下一页按钮设置为不能点击
                        if(currentPage === json.data.maxPage) {
                            $('#page ul li:last').attr('class', 'disabled').html('<span>»</span>');
                        }

                    }
                },
                error: function() {
                    alert('删除失败');
                }
            });
        });
    </script>
@stop()
