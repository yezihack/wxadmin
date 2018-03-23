@extends('layout')
@section('title', $title)
@section('style')
    <style>
        .layui-upload-img {
            max-width: 190px;
            height: 92px;
            margin: 0 10px;
        }

        #upload_pic_id {
            width: 190px;
        }
    </style>
@stop
@section('body')
    <div class="layui-form layui-form-pane" onsubmit="return false;">
        <blockquote class="layui-elem-quote">{{$title}}
            <span class="layui-word-aux"></span>
        </blockquote>
        <div class="layui-form-item">
            <label class="layui-form-label">消息名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" required lay-verify="required" lay-verType="tips" placeholder="请输入消息名称"
                       autocomplete="off"
                       class="layui-input" value="{{$item->name or ''}}">
            </div>
            <div class="layui-form-mid layui-word-aux">不在微信里显示的，只是取个别名而已，易于识别本条消息做什么的</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">消息类型</label>
            <div class="layui-input-inline">
                <select name="msg_type_code" lay-filter="msg_type_code" @if($pid >0) disabled="disabled" @endif>
                    <option value="">选择类型</option>
                    @foreach($type_list as $key => $val)
                        @if($pid > 0)
                            <option value="{{$key}}" @if($key == 'news') selected @endif>{{$val}}</option>
                        @elseif(isset($item))
                            <option value="{{$key}}" @if($item->event_code == $key) selected @endif>{{$val}}</option>
                        @else
                            <option value="{{$key}}">{{$val}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">这个非常重要</div>
        </div>
        <div class="layui-form-item" id="title_id">
            <label class="layui-form-label">消息标题</label>
            <div class="layui-input-inline" style="width:350px;">
                <input type="text" name="title" placeholder="请输入消息标题" autocomplete="off" class="layui-input"
                       value="{{$item->title or ''}}">
            </div>
            <div class="layui-form-mid layui-word-aux"><span class="layui-badge-dot"></span>微信里显示</div>
        </div>
        <div class="layui-form-item" id="url_id">
            <label class="layui-form-label">消息链接</label>
            <div class="layui-input-inline" style="width:350px;">
                <input type="text" name="url" placeholder="请输入消息链接" autocomplete="off" class="layui-input"
                       value="{{$item->url or ''}}">
            </div>
            <div class="layui-form-mid layui-word-aux"><span class="layui-badge-dot"></span>微信里显示</div>
        </div>
        <div class="layui-form-item" id="pic_url_id">
            <label class="layui-form-label">图片链接</label>
            <div class="layui-input-inline">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="upload_pic_id">上传图片</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="pic_preview_id">
                        <p id="upload_preview_id"></p>
                    </div>
                </div>
            </div>
            <div class="layui-form-mid layui-word-aux"><span class="layui-badge-dot"></span>图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
            </div>
        </div>
        <div class="layui-form-item" id="desc_id">
            <label class="layui-form-label">消息描述</label>
            <div class="layui-input-inline" style="width:350px;">
                <textarea class="layui-textarea" name="desc" id="LAY_desc" style="display: none"
                          placeholder="消息描述，支持添加链接">{{$item->desc or ''}}</textarea>
            </div>
            <div class="layui-form-mid layui-word-aux"><span class="layui-badge-dot"></span>微信里显示</div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type="button" class="layui-btn" lay-submit lay-filter="success" value="确认提交">
                <a href="javascript:window.history.back()" class="layui-btn layui-btn-primary">返回</a>
                <input type="hidden" name="pic_url" value="">
                <input type="hidden" name="parent_id"
                       value="{{$pid > 0 ? $pid : (isset($item) ? $item->parent_id :0)}}">
                <input type="hidden" name="id" value="{{$item->id or 0}}">
            </div>
        </div>
    </div>
@stop
@section('script')
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

            var init_load = function () {
                var src = ss.getItem(upload_key);
                $("#pic_preview_id").attr('src', src);
                $("input[name='pic_url']").val(src);
            };
            init_load();
            layui.use(['form', 'layedit', 'upload'], function () {
                var form = layui.form
                    , layedit = layui.layedit
                    , upload = layui.upload;

                //普通图片上传
                var load_index;
                var uploadInst = upload.render({
                    elem: '#upload_pic_id'
                    , accept: 'images'
                    , exts: 'jpg|png'
                    , size: 10240
                    , url: '{{route('upload')}}'
                    , before: function (obj) {
                        load_index =  layer.load();
                        //预读本地文件示例，不支持ie8
                        obj.preview(function (index, file, result) {
                            $('#pic_preview_id').attr('src', result); //图片链接（base64）
                        });
                    }
                    , done: function (res) {
                        layer.close(load_index);
                        //如果上传失败
                        if (res.status > 0) {
                            return layer.msg('上传失败');
                        }
                        //上传成功
                        ss.setItem(upload_key, res.data);
                        $("input[name='pic_url']").val(res.data);
                    }
                    , error: function () {
                        layer.close(load_index);
                        //演示失败状态，并实现重传
                        var demoText = $('#upload_preview_id');
                        demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-mini demo-reload">重试</a>');
                        demoText.find('.demo-reload').on('click', function () {
                            uploadInst.upload();
                        });
                    }
                });
                //初使富文本框
                var layindex = layedit.build('LAY_desc', {
                    tool: ['unlink']
                    , height: 250
                });

                //不同类型显示不一样
                form.on('select(msg_type_code)', function (data) {
                    changeSelect($.trim(data.value));
                });
                form.on('submit(success)', function (data) {
                    var desc = layedit.getText(layindex);
                    var field = data.field;
                    field.desc = desc;
                    $.post("{{route('msg.add')}}", field, function (rev) {
                        if (rev.status === 0) {
                            ss.removeItem(upload_key);
                            layer.closeAll();
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

                function changeSelect(event_code) {
                    switch (event_code) {
                        case 'text'://
                            $("#title_id").hide();
                            $("#url_id").hide();
                            $("#pic_url_id").hide();
                            $("#desc_id").show();
                            break;
                        case 'news':
                            $("#title_id").show();
                            $("#url_id").show();
                            $("#pic_url_id").show();
                            $("#desc_id").show();
                            break;
                        case 'link':
                            $("#title_id").show();
                            $("#url_id").show();
                            $("#pic_url_id").hide();
                            $("#desc_id").show();
                            break;
                        default:
                            $("#title_id").show();
                            $("#url_id").show();
                            $("#pic_url_id").show();
                            $("#desc_id").show();
                            break;
                    }
                }

                //编辑使用
                changeSelect("{{$item->event_code or ''}}");
                $("input[name='pic_url']").val('{{$item->pic_url or ''}}');
                $("#pic_preview_id").attr('src', '{{$item->pic_url or ''}}');
            });
        });
    </script>
@stop