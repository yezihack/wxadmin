@extends('layout')
@section('title', $title)
@section('style')
    <style>
    </style>
@stop
@section('body')
    <form class="layui-form layui-form-pane" onsubmit="return false;">
        <blockquote class="layui-elem-quote">{{$title}}
            <span class="layui-word-aux">自定义菜单名称,一级菜单数组，个数应为1~3个,二级菜单数组，个数应为1~5个</span>
        </blockquote>
        <div class="layui-form-item">
            <label class="layui-form-label">菜单名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verType="tips" required lay-verify="required|name_min_max"
                       placeholder="请输入菜单名称"
                       autocomplete="off" class="layui-input" value="{{$menu->name}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择父菜单</label>
            <div class="layui-input-inline">
                <select name="parent_id" lay-verify="">
                    <option value="0">一级菜单</option>
                    @foreach($parent_list as $key => $val)
                        <option value="{{$key}}" @if($menu->parent_id == $key) selected @endif>{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择类型</label>
            <div class="layui-input-inline">
                <select name="type">
                    <option value="">选择类型</option>
                    @foreach($type_list as $key => $val)
                        <option value="{{$key}}" @if($menu->type == $key) selected @endif>{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">定义值</label>
            <div class="layui-input-inline" style="width:350px;">
                <input type="text" name="value" placeholder="请输入定义值"
                       autocomplete="off" class="layui-input" value="{{$menu->value}}">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="success">确认提交</button>
                <a href="javascript:window.history.back()" class="layui-btn layui-btn-primary">返回</a>
                <input type="hidden" name="id" value="{{$menu->id}}">
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
                form.verify({
                    //我们既支持上述函数式的方式，也支持下述数组的形式
                    //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
                    name_min_max: [
                        /^[\S]{1,16}$/
                        , '菜单标题，不超过16个字节'
                    ]
                });
                form.on('submit(success)', function (data) {
                    var field = data.field;
                    $.post("{{route('menu.edit')}}", field, function (rev) {
                        if (rev.status === 0) {
                            layer.alert(rev.msg, function () {
                                if (typeof(rev.data) === 'undefined' || rev.data === '') {
                                    window.history.go(-1);
                                } else {
                                    window.location.href = rev.data;
                                }
                            });
                        } else {
                            Msg.warn(rev.msg);
                        }

                    });
                    return false;
                });
            });
        });
    </script>
@stop