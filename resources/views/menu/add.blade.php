@extends('layout')
@section('title', $title)
@section('style')
    <style>
        .layui-form-label {
            width: 130px !important;
        }
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
                       autocomplete="off" class="layui-input" value="{{$item->name or ''}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择父菜单</label>
            <div class="layui-input-inline">
                <select name="parent_id" lay-verify="">
                    <option value="0">一级菜单</option>
                    @foreach($parent_list as $key => $val)
                        @if($pid > 0)
                            <option value="{{$key}}" @if($pid == $key) selected @endif>{{$val}}</option>
                        @elseif(isset($item))
                            <option value="{{$key}}" @if($item->parent_id == $key) selected @endif>{{$val}}</option>
                        @else
                            <option value="{{$key}}">{{$val}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择类型</label>
            <div class="layui-input-inline">
                <select name="type" lay-filter="type" required lay-verify="required" lay-verType="tips">
                    <option value="">选择类型</option>
                    @foreach($type_list as $key => $val)
                        <option value="{{$key}}"
                                @if(isset($item) && $item->type == $key) selected @endif>{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item" id="value_id">
            <label class="layui-form-label">定义值</label>
            <div class="layui-input-inline" style="width:350px;">
                <input type="text" name="view_value" placeholder="请输入定义值"
                       autocomplete="off" class="layui-input" value="@if(isset($item) && $item->type=='view'){{$item->value}}@endif">
            </div>
        </div>
        <div class="layui-form-item" id="msg_id">
            <label class="layui-form-label">关联事件</label>
            <div class="layui-input-inline">
                <select name="click_value">
                    <option value="">选择关联事件</option>
                    @foreach($event_list as $key => $val)
                        @if(isset($item) && $item->type == 'click')
                            <option value="{{$key}}" @if($item->value == $key) selected @endif>{{$val}}</option>
                        @else
                            <option value="{{$key}}">{{$val}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="layui-form-mid">
                <a class="layui-btn layui-btn-xs layui-btn-normal" href="{{route('event.add', ['type' => 'click'])}}">新建事件</a>
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
                $("#value_id").hide();
                $("#msg_id").hide();
                form.on('select(type)', function (data) {
                    var value = data.value;
                    changeInput(value);
                });
                form.on('submit(success)', function (data) {
                    console.log(data);
                    var field = data.field;
                    if (field.type === 'view') {
                        var reg = /(^#)|(^http(s*):\/\/[^\s]+\.[^\s]+)/;
                        if (!reg.test(field.view_value)) {
                            var _input = $("input[name='view_value']");
                            layer.tips("链接格式不正确", _input, {tips: 1});
                            return false;
                        }
                    }
                    $.post("{{route('menu.add')}}", field, function (rev) {
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

                function changeInput(value) {
                    switch (value) {
                        case 'click':
                            $("#value_id").hide();
                            $("#msg_id").show();
                            break;
                        case 'view':
                            $("#value_id").show();
                            $("#msg_id").hide();
                            break;
                        default:
                            $("#value_id").hide();
                            $("#msg_id").hide();
                            break;
                    }
                }

                changeInput('{{$item->type or ''}}');
            });
        });
    </script>
@stop