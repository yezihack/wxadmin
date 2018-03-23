@extends('layout')
@section('title', $title)
@section('style')
    <style>
    </style>
@stop
@section('body')
    <form class="layui-form layui-form-pane" onsubmit="return false;">
        <blockquote class="layui-elem-quote">{{$title}}</blockquote>
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-inline">
                <input type="text" name="username" required lay-verify="required" lay-verType="tips" placeholder="请输入用户名"
                       autocomplete="off" class="layui-input" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password" required lay-verify="required" lay-verType="tips" placeholder="请输入密码"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password2" required lay-verify="required" lay-verType="tips" placeholder="请输入密码"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input class="layui-btn" lay-submit lay-filter="success" value="立即提交">
                <a href="javascript:window.history.back()" class="layui-btn layui-btn-primary">返回</a>
            </div>
        </div>
    </form>
@stop
@section('script')
    <script>
        $(function () {
            $(document).keyup(function (event) {
                if (event.keyCode == 13) {
                    $("#submit").trigger("click");
                }
            });
            layui.use(['form'], function () {
                var form = layui.form;
                form.on('submit(success)', function (data) {
                    var field = data.field;
                    $.post("{{route('user.add')}}", field, function (rev) {
                        layer.alert(rev.msg, function () {
                            if (typeof(rev.data) === 'undefined' || rev.data === '') {
                                window.history.go(-1);
                            } else {
                                window.location.href = rev.data;
                            }
                        });
                    });
                    return false;
                });
            });
        });
    </script>
@stop