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
        @if(isset($webSite->id))<input type="hidden" name="website_id" value="{{ $webSite->id }}">@endif
        <div class="form-group">
            <label for="gamename">游戏名称</label>
            <input type="text" id="gamename" class="form-control" name="WebSite[gamename]" placeholder="请输入游戏名" value="{{ isset($webSite->gamename) ? $webSite->gamename : '' }}">
        </div>

        <div class="form-group">
            <label for="d_author">默认作者名</label>
            <input type="text" id="d_author" class="form-control" name="WebSite[d_author]" placeholder="请输入默认作者名" value="{{ isset($webSite->d_author) ? $webSite->d_author : '' }}">
        </div>

        <div class="form-group">
            <label for="m_tag">移动端文件夹</label>
            <input type="text" id="m_tag" class="form-control" name="WebSite[m_tag]" placeholder="请输入文件夹名称" value="{{ isset($webSite->m_tag) ? $webSite->m_tag : '' }}">
        </div>

        <div class="form-group">
            <label for="copyfile">需要复制的文件</label>
            <textarea id="copyfile" class="form-control" name="WebSite[copyfile]" rows="3">{{ isset($webSite->copyfile) ? $webSite->copyfile : '' }}</textarea>
        </div>

        <div class="form-group">

            <a class="btn btn-default pull-left" href="{{ url('website') }}">返回</a>

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
                url: '{{ !isset($webSite->id) && $edittype === 'add'  ? url('/api/website/add') : url('/api/website/update', $webSite->id) }}',
                data: $("#updateForm").serialize(),
                success: function(json) {
                    if(json && json.status === 1) {
                        $('#submit_btn').text('成功');
                        setTimeout(function() {
                            $('#submit_btn').text('提交');
                            $('#submit_btn').removeClass('disabled').removeAttr('disabled');
                        }, 1000);
                    }
                },
                error: function() {
                    alert('失败');
                }
            });
        });
    </script>
@stop()