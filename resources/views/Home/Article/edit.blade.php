@extends('layouts.home')

@section('headercode')

    @include('UEditor::head')

    <style>
        #submit_btn {
            transition:all .5s;
        }
    </style>
@stop()
@section('content')
    <form id="updateForm" method="POST" class="form-horizontal" role="form">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        @if(isset($article->id))<input type="hidden" name="article_id" value="{{ $article->id }}">@endif
        <div class="form-group">
            <div class="col-sm-8">
                <input type="text" class="form-control" name="Article[title]" placeholder="请输入标题" value="{{ isset($article->title) ? $article->title : '' }}">
            </div>
        </div>

        <!-- 加载编辑器的容器 -->
        <script id="container" name="Article[content]" type="text/plain">{!! isset($article->content) ? $article->content : '' !!}</script>

        <div class="form-group col-sm-3">
            <label class="control-label">修改发布时间</label>
            <!--指定 date标记-->
            <div class='input-group date' id='datetimepicker2'>
                <input type='text' class="form-control col-sm-4" name="Article[created_at]" value="{{ isset($article->created_at) ? $article->created_at : '' }}" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>

        <div class="form-group col-sm-3 col-sm-push-1">
            <label class="control-label" for="">属于游戏</label>
            <select id="wsid" class="form-control" name="Article[wsid]">
                @foreach($gamelist as $game)
                    <option value="{{ $game->id }}" {{ isset($article->wsid) && $game->id == $article->wsid ? 'class=text-primary selected' : '' }}>{{ $game->gamename }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-sm-3 col-sm-push-2">
            <label class="control-label" for="">类型</label>
            <select id="type" class="form-control" name="Article[type]">
                @foreach($typelist as $type)
                    <option value="{{ $type->id }}" {{ isset($article->type) && $type->id == $article->type ? 'class=text-primary selected' : '' }}>{{ $type->t_desc }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="control-label" for="">&nbsp;</label>
            <div class="col-sm-4 col-sm-push-2">
                <button id="submit_btn" type="button" class="btn btn-default col-sm-4">提交</button>
            </div>
        </div>
    </form>

    @include('common.alert')
@stop()

@section('footercode')
    <script type="text/javascript">
        // 实例化编辑器
        var ue = UE.getEditor('container');
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
        });

        $('#submit_btn').click(function() {
            $(this).addClass('disabled').attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: '{{ !isset($article->id) && $edittype === 'add'  ? url('/api/article/add') : url('/api/article/update', $article->id) }}',
                data: $("#updateForm").serialize(),
                success: function(json) {
                    if(json) {
                        if(json.status === 1) {
                            // 成功后修改按钮提示和下拉框字体颜色
                            $('#submit_btn').html('成功').addClass('btn-success');
                            $('#wsid option,#type option').removeClass('text-primary');
                            $('#wsid option:selected,#type option:selected').addClass('text-primary');

                            build(json.data.id);

                            // 2秒后恢复按钮
                            setTimeout(function() {
                                // 如果是新增 跳转到文章预览页
                                @if(isset($edittype) && $edittype === 'add') window.location.href = '{{ url('/article') }}/' + json.data.id; @endif
                                $('#submit_btn').html('提交').removeClass('btn-success disabled').removeAttr('disabled');
                            }, 2000);
                        } else {
                            $('#submit_btn').html('失败').addClass('btn-danger');
                            setTimeout(function() {
                                $('#submit_btn').html('提交').removeClass('btn-danger disabled').removeAttr('disabled');
                            }, 2000);
                        }
                    }
                },
                error: function() {
                    $('#submit_btn').html('失败').addClass('btn-danger');
                    setTimeout(function() {
                        $('#submit_btn').html('提交').removeClass('btn-danger disabled').removeAttr('disabled');
                    }, 2000);
                }
            });
        });

        function build(id) {
            $.ajax({
                type: 'POST',
                url: '{{ url('/api/build/all') }}',
                data: {
                    'id': id,
                    'wsid': {{ isset($article->wsid) ? $article->wsid : -1 }}
                },
                success: function(json) {
                    if(json)
                        alert('已自动重新生成文章');
                }
            });
        }
    </script>
@stop()