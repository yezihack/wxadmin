@extends('layout')
@section('title', $title)
@section('style')
    <style>
        .ml5 {
            margin-left: 5px;
        }
    </style>
@stop
@section('body')
    <blockquote class="layui-elem-quote">{{$title}}</blockquote>
    <div class="layui-form-item">
        <a layTips="关键字用于公众号用户输入相应的关键字回复的消息|3|#3595CC" class="layui-btn layui-btn-primary ml5" href="{{route('event.add', ['type' => 'keywords'])}}"><i
                    class="fa fa-plus-circle fa-fw"></i>添加关键字事件</a>
        <a layTips="按扭事件和菜单类型为“按扭事件”菜单项目使用的|3|#3595CC" class="layui-btn layui-btn-normal  ml5" href="{{route('event.add', ['type' => 'click'])}}"><i
                    class="fa fa-plus-circle fa-fw"></i>添加按扭事件</a>
        <a layTips="订阅事件是用户关注公众号回复的内容消息|3|#3595CC"  class="layui-btn layui-btn-warm ml5" href="{{route('event.add', ['type' => 'subscribe'])}}"><i
                    class="fa fa-plus-circle fa-fw"></i>添加订阅事件</a>
        <a class="layui-btn ml5 layui-hide" href="{{route('event.add', ['type' => 'unsubscribe'])}}"><i
                    class="fa fa-plus-circle fa-fw"></i>添加取消订阅事件</a>
        <a class="layui-btn layui-btn-danger ml5 layui-hide" href="{{route('event.add', ['type' => 'scan'])}}"><i
                    class="fa fa-plus-circle fa-fw"></i>添加扫描事件事件</a>
    </div>
    <div class="layui-collapse">
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">帮助</h2>
            <div class="layui-colla-content layui-show">
                <ul>
                    <li><span class="layui-badge layui-bg-blue mr5">1</span>事件管理：关键字事件，按扭事件，订阅消息</li>
                    <li><span class="layui-badge layui-bg-blue mr5">2</span>关键字事件：设置关键字，如hello,在公众号里回复hello则返回相响应的事件，如果重名关键字，只响应最新的事件
                    </li>
                    <li><span class="layui-badge layui-bg-blue mr5">3</span>按扭事件：需要设置识标，与菜单配合使用</li>
                    <li><span class="layui-badge layui-bg-blue mr5">4</span>订阅消息：当新的用户关注时，响应事件</li>
                    <li><span class="layui-badge layui-bg-blue mr5">5</span>状态：默认为正常使用，暂停则不会响应事件</li>
                    <li class="layui-bg-orange"><span class="layui-badge layui-bg-blue mr5">6</span>删除：永久删除，慎重使用</li>
                    <li><span class="layui-badge layui-bg-blue mr5">7</span>编辑：可以修改已设置项</li>
                    <li><span class="layui-badge layui-bg-blue mr5">8</span>关联模板消息：即消息管理</li>
                </ul>
            </div>
        </div>
    </div>
    <table id="table-list" lay-filter="tableLay"></table>

    <script type="text/html" id="hrefTpl">
        <a class="color:#01AAED !important" href="{{route('msg.edit')}}?id=@{{d.msg_id}}">@{{d.msg_name}}</a>
    </script>
    @verbatim
        <script type="text/html" id="switchTpl">
            <input type="checkbox" name="" value="@{{d.id}}" lay-skin="switch" lay-text="使用|禁用"
                   lay-filter="change" {{d.is_use== 1 ? 'checked' : '' }}>
        </script>
        <script type="text/html" id="barTable">
            <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>
    @endverbatim
@stop
@section('script')
    <script>
        layui.use(['table', 'form', 'element'], function () {
            var table = layui.table;
            var form = layui.form;
            var element = layui.element;
            //第一个实例
            table.render({
                elem: '#table-list'
                , height: 'auto'
                , method: 'post'
                , url: "{{route('event.list')}}" //数据接口
                , page: true //开启分页
                , size: ''
                , cellMinWidth: 60
                , limit: 100
                , limits: ['100']
                , cols: [[ //表头
                    {field: 'id', title: 'ID', sort: true}
                    , {field: 'type_name', title: '事件类型'}
                    , {field: 'content', title: '关键字/标识字段'}
                    , {field: 'msg_name', title: '关联模板信息', templet: '#hrefTpl'}
                    , {field: 'is_use_format', title: '状态', templet: '#switchTpl'}
                    , {field: 'update_format', title: '修改时间', sort: true}
                    , {title: '操作', templet: '#barTable', width: 185}
                ]]
            });
            //监听radio操作
            form.on('switch(change)', function (obj) {
                console.log(obj);
                var pp = {};
                pp.id = this.value;
                pp.value = obj.elem.checked ? 1 : 0;
                $.post("{{route('event.change')}}", pp, function (rev) {
                    if (rev.status === 0) {
                        Msg.success(rev.msg);
                    } else if (rev.status === 400) {
                        layer.alert(rev.msg, function () {
                            window.location.reload();
                        });
                    } else {
                        Msg.warn(rev.msg);
                    }
                }, 'json');
            });
            table.on('tool(tableLay)', function (obj) {
                var data = obj.data;
                if (obj.event === 'addChild') {
                    window.location.href = "{{route('event.add')}}?id=" + data.id;
                } else if (obj.event === 'del') {
                    var tips = data.parent_id === 0 ? '您删除的是一级菜单,连同子菜单一起删除吗?' : '真的删除行么';
                    layer.confirm(tips, {icon: 2}, function (index) {
                        $.post("{{route('event.del')}}", {id: data.id}, function (rev) {
                            if (rev.status === 0) {
                                obj.del();
                            }
                            layer.msg(rev.msg);
                        });
                        layer.close(index);
                    });
                } else if (obj.event === 'edit') {
                    window.location.href = "{{route('event.edit')}}?id=" + data.id + '&type=' + data.type;
                }
            });
        });
    </script>
@stop