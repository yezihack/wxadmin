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
            <label class="layui-form-label">二维码类型</label>
            <div class="layui-input-inline">
                <select name="type" required lay-verify="required" lay-verType="tips" lay-filter="type">
                    <option value="">选择类型</option>
                    @foreach($type_list as $key => $val)
                        <option value="{{$key}}">{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item" id="day_id" style="display: none;">
            <label class="layui-form-label">有效时间</label>
            <div class="layui-input-inline">
                <select name="day">
                    <option value="">选择天数</option>
                    @foreach(range(1, 30) as $val)
                        <option value="{{$val}}" @if($val == 30) selected @endif>{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">scene_str</label>
            <div class="layui-input-inline">
                <input name="scene_str" class="layui-input" value="{{$scene_str}}" required lay-verify="required" lay-verType="tips">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">渠道名称</label>
            <div class="layui-input-inline">
                <textarea name="remark" class="layui-textarea" placeholder="请输入渠道名称" required lay-verify="required" lay-verType="tips"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="success">生成</button>
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
                var form = layui.form;
                form.on('select(type)', function (data) {
                    console.log(data);
                    if(data.value === 'QR_STR_SCENE') {
                        $("#day_id").show();
                    } else {
                        $("#day_id").hide();
                    }
                });
                form.on('submit(success)', function (data) {
                    var field = data.field;
                    $.post("{{route('qr.add')}}", field, function (rev) {
                        if (rev.status === 0) {
                            layer.alert(rev.msg, function () {
                                if (typeof(rev.data) === 'undefined' || rev.data === '') {
                                    window.history.go(-1);
                                } else {
                                    window.location.href = rev.data;
                                }
                            });
                        } else {
                            Alert.alert(rev.msg);
                        }
                    });
                    return false;
                });
            });
        });
    </script>
@stop