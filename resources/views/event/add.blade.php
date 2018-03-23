@extends('layout')
@section('title', $title)
@section('style')
    <style>
        .layui-form-label {
            width: 140px !important;
        }
    </style>
@stop
@section('body')
    <form class="layui-form layui-form-pane" onsubmit="return false;">
        <blockquote class="layui-elem-quote">{{$title}}
            <span class="layui-word-aux"></span>
        </blockquote>
        <div class="layui-form-item">
            <label class="layui-form-label">消息类型</label>
            <div class="layui-input-inline">
                <select name="type" lay-filter="type" disabled="disabled">
                    <option value="">选择类型</option>
                    @foreach($type_list as $key => $val)
                        <option value="{{$key}}" @if($type == $key) selected @endif>{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if(in_array($type, ['keywords', 'click']))
            <div class="layui-form-item" id="content_id">
                <label class="layui-form-label">{{$type == 'keywords' ? '关键字' : '标识字段'}}</label>
                <div class="layui-input-inline" style="width:350px;">
                    <input type="text" required lay-verify="required{{$type=='click'?'|isChar':''}}" lay-verType="tips"
                           name="content" placeholder="请输入{{$type == 'keywords' ? '关键字' : '标识字段'}}" autocomplete="off"
                           class="layui-input"
                           value="{{$item->content or ''}}">
                </div>
            </div>
        @endif
        <div class="layui-form-item" id="url_id">
            <label class="layui-form-label">关联模板信息</label>
            <div class="layui-input-inline" style="width:350px;">
                <select name="msg_id" lay-search required lay-verify="required" lay-verType="tips">
                    <option value="">选择类型</option>
                    @foreach($msg_list as $key => $val)
                        <option value="{{$key}}"
                                @if(isset($item) && $item->msg_id == $key) selected @endif>{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input class="layui-btn" lay-submit lay-filter="success" value="确认提交">
                <a href="javascript:window.history.back()" class="layui-btn layui-btn-primary">返回</a>
                <input type="hidden" name="id" value="{{$item->id or 0}}">
            </div>
        </div>
    </form>
@stop
@section('script')
    <script src="{{asset('common/js/selectTool.js')}}?id={{time()}}"></script>
    <script>
        $(function () {
            var ls = window.localStorage;
            var ss = window.sessionStorage;
            var upload_key = 'upload_src';
            $(document).keyup(function (event) {
                if (event.keyCode === 13) {
                    $("#submit").trigger("click");
                }
            });

            layui.use(['form', 'layedit', 'upload'], function () {
                var form = layui.form
                    , layedit = layui.layedit
                    , upload = layui.upload;
                form.verify({
                    isChar: function (value, item) { //value：表单的值、item：表单的DOM对象
                        if (!new RegExp("^[a-zA-Z0-9_]+$").test(value)) {
                            return '标识字段只允许字母或数字';
                        }
                    }
                    //我们既支持上述函数式的方式，也支持下述数组的形式
                    //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
                    , pass: [
                        /^[\S]{6,12}$/
                        , '密码必须6到12位，且不能出现空格'
                    ]
                });
                form.on('submit(success)', function (data) {
                    var field = data.field;
                    $.post("{{route('event.add')}}", field, function (rev) {
                        if (rev.status === 0) {
                            ss.removeItem(upload_key);
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