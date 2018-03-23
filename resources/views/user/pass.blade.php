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
            <label class="layui-form-label">密码框</label>
            <div class="layui-input-inline">
                <input type="password" name="password" required lay-verify="required" placeholder="请输入密码"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password2" required lay-verify="required" placeholder="请输入密码"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="success">立即提交</button>
                <button id="reset" class="layui-btn layui-btn-primary">重置</button>
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
                    $.post("{{route('user.pass')}}", field, function (rev) {
                        layer.alert(rev.msg, {skin: 'layui-layer-lan', btn: ['跳转', '确认']}, function () {
                            console.log(typeof(rev.data));
                            if (typeof(rev.data) === 'undefined' || rev.data === '') {
                                window.history.go(-1);
                            } else {
                                window.location.href = rev.data;
                            }
                        }, function (index) {
                            layer.close(index);
                        });
                    });
                    return false;
                });
            });
            $("#reset").click(function () {
                $("input[name='password']").val('');
                $("input[name='password2']").val('');
            });
        });
    </script>
@stop