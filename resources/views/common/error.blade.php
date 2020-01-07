@extends('layouts.home')

@section('content')
    <div class="panel panel-danger">
        <div class="panel-body">
            提示
        </div>
        <div class="panel-footer">{{ Session::has('message') ? Session::get('message') : '页面错误' }}</div>
    </div>
@stop()