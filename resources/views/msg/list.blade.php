@extends('layout')
@section('title', $title)
@section('body')
    <blockquote class="layui-elem-quote">{{$title}}</blockquote>
    <div class="layui-form-item">
        <a class="layui-btn ml5" layTips="模板消息就是显示在公众号里的信息哦|3|#3595CC" href="{{route('msg.add')}}"><i
                    class="fa fa-plus-circle fa-fw"></i>添加模板消息</a>
    </div>
    <div class="layui-collapse">
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">帮助</h2>
            <div class="layui-colla-content layui-show">
                <ul>
                    <li><span class="layui-badge layui-bg-cyan mlr5">1</span>消息类型分两种：文字消息，图文消息，配合事件管理使用，见：事件管理</li>
                    <li><span class="layui-badge layui-bg-cyan mlr5">2</span>文字类型：纯文本消息</li>
                    <li><span class="layui-badge layui-bg-cyan mlr5">3</span>图文消息：有图有文，可以自定义图片，链接，描述，一个图文可以添加7个子图文</li>
                    <li><span class="layui-badge layui-bg-cyan mlr5">4</span>消息名称：用于识别，微信里不显示</li>
                    <li><span class="layui-badge layui-bg-cyan mlr5">5</span>添加子图文：消息类型为图文消息，在操作列中点击“添加子图文”</li>
                    <li class="layui-bg-orange mlr5"><span class="layui-badge layui-bg-cyan">6</span>删除：永久删除，慎重使用。如果是删除主图文，子图文一并删除。
                    </li>
                    <li><span class="layui-badge layui-bg-cyan mlr5">7</span>编辑：可以修改已设置项</li>
                </ul>
            </div>
        </div>
    </div>
    <table id="table-list" lay-filter="tableLay"></table>
    <script type="text/html" id="switchTpl">
        <input type="checkbox" name="" value="@{{d.id}}" lay-skin="switch" lay-text="使用|禁用"
               lay-filter="change" @{{d.is_use== 1 ? 'checked' : '' }}>
    </script>
    @verbatim
        <script type="text/html" id="barTable">
            {{# if(d.parent_id == 0 && d.event_code == 'news'){  }}
            <a class="layui-btn layui-btn-xs" lay-event="addChild">添加子图文</a>
            {{# } }}
            <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    @endverbatim
@stop
@section('script')
    <script>
        layui.use(['table', 'form', 'element'], function () {
            var table = layui.table;
            var element = layui.element;
            var form = layui.form;
            //第一个实例
            table.render({
                elem: '#table-list'
                , height: 'auto'
                , method: 'post'
                , url: "{{route('msg.list')}}" //数据接口
                , page: true //开启分页
                , size: ''
                , cellMinWidth: 60
                , limit: 100
                , limits: ['100']
                , cols: [[ //表头
                    {field: 'id', title: 'ID', sort: true}
                    , {field: 'msg_type_name', title: '消息类型'}
                    , {field: 'name', title: '消息名称'}
                    , {field: 'update_format', title: '修改时间', sort: true}
                    , {title: '操作', templet: '#barTable', unresize: true, width: 185}
                ]]
            });
            table.on('tool(tableLay)', function (obj) {
                var data = obj.data;
                if (obj.event === 'addChild') {
                    window.location.href = "{{route('msg.add')}}?id=" + data.id;
                } else if (obj.event === 'del') {
                    var tips = data.parent_id === 0 ? '您删除的是一级菜单,连同子菜单一起删除吗?' : '真的删除行么';
                    layer.confirm(tips, {icon: 2}, function (index) {
                        $.post("{{route('msg.del')}}", {id: data.id}, function (rev) {
                            if (rev.status === 0) {
                                obj.del();
                                if (typeof rev.data !== 'undefined') {
                                    window.location.reload();
                                }
                            }
                            layer.msg(rev.msg);
                        });
                        layer.close(index);
                    });
                } else if (obj.event === 'edit') {
                    window.location.href = "{{route('msg.edit')}}?id=" + data.id;
                }
            });
        });
    </script>
@stop