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
        @if(isset($website->id))<input type="hidden" name="website_id" value="{{ $website->id }}">@endif
        <div class="form-group">
            <label for="gamename">游戏名称</label>
            <input type="text" id="gamename" class="form-control" name="WebSite[gamename]" placeholder="请输入游戏名" value="{{ isset($website->gamename) ? $article->gamename : '' }}">
        </div>

        <div class="form-group">
            <label for="d_author">默认作者名</label>
            <input type="text" id="d_author" class="form-control" name="WebSite[d_author]" placeholder="请输入默认作者名" value="{{ isset($website->d_author) ? $article->d_author : '' }}">
        </div>

        <div class="form-group">
            <label for="copyfile">需要复制的文件</label>
            <textarea id="copyfile" class="form-control" rows="3">{{ isset($website->copyfile) ? $article->copyfile : '' }}</textarea>
        </div>

        <div class="form-group">
            <label class="control-label" for="">&nbsp;</label>
            <div class="col-sm-4">
                <button id="submit_btn" type="button" class="btn btn-success col-sm-4">提交</button>
            </div>
        </div>
    </form>
@stop()

@section('footercode')
    <script type="text/javascript">

        $('#submit_btn').click(function() {
            $(this).addClass('disabled').attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: '{{ !isset($website->id) && $edittype === 'add'  ? url('/api/website/add') : url('/api/website/update', $website->id) }}',
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
                    'wsid': 1
                },
                success: function(json) {

                }
            });
        }
    </script>
@stop()