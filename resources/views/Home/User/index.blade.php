@extends('layouts.home')

@section('content')
    <form role="form">
        <div class="form-group">
            <label for="name">用户设置</label>
            <span class="oi oi-person"></span>
        </div>

        <div class="form-group">
            <label for="name">显示站点 <span style="color: #999;">按 Ctrl 键多选</span></label>
            <select id="showWebSite" multiple class="form-control">
                @foreach($webSites as $webSite)
                    <option value="{{ $webSite->id }}">{{ $webSite->gamename }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="configCode">设置代码</label>
            <textarea id="configCode" class="form-control" style="resize:none;" rows="3"></textarea>
        </div>

        <div class="btn-group">
            <button id="clearConfig" class="btn btn-danger">清空配置</button>
        </div>
    </form>
@stop()

@section('footercode')
<script>

// window.localStorage.setItem();
$(function() {

    var UserConfig = {
        webSite: [],
    };

    // JSON.parse       string转json
    // JSON.stringify   json转string

    initConfigView();

    $('#configCode').val(window.localStorage.getItem('UserConfig'));

    // 设置需要显示的网站
    $('#showWebSite').change(function(){

        // 先清空原有设置
        UserConfig.webSite = [];

        // 循环所有选中的网站
        $('#showWebSite option:selected').each(function() {
            var wsid = $(this).val();
            // 添加网站ID
            UserConfig.webSite.push(wsid);
        });

        refreshConfig(UserConfig);
    });

    // 清空配置
    $('#clearConfig').click(function() {
        window.localStorage.setItem('UserConfig', '{}');
        $('#configCode').val('');
    });

});

// 更改配置后执行的刷新配置 localStorage 和 页面 都会更新到最新的配置
function refreshConfig(UserConfig) {
    // 是否支持 localStorage

    if(!window.localStorage) {
        $('#configCode').attr('readonly', 'readonly').val('您的浏览器不支持localStorage');
        return;
    }

    var jsonConfig = JSON.stringify(UserConfig);

    window.localStorage.setItem('UserConfig', jsonConfig);

    $('#configCode').val(jsonConfig);
}

// 初始化配置显示，第一次进入页面后读取配置，把页面中的配置项调到相应选项
function initConfigView() {

    var UserConfig = JSON.parse(window.localStorage.getItem('UserConfig'));

    if(UserConfig === undefined
        || UserConfig === null
        || typeof UserConfig.webSite === 'undefined'
    ) return false;

    if($('#showWebSite option').length > 0) {
        $('#showWebSite option').each(function() {
            var wsid = $(this).val();
            if($.inArray(wsid, UserConfig.webSite) !== -1) {
                $(this).attr('selected', true);
            }
        });
    }

}

</script>
@stop()