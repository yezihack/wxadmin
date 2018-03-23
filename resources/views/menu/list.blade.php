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
        <a class="layui-btn ml5" layTips="添加一级菜单，二级菜单在一级菜单上添加哦|3|#3595CC" href="{{route('menu.add')}}"><i
                    class="fa fa-plus-circle fa-fw"></i>添加一级菜单</a>
        <button class="layui-btn layui-btn-normal ml5" layTips="修改菜单后，需要点击同步，公众号才生效的|3|#3595CC" id="menu_sync"><i
                    class="fa fa-random fa-spin fa-fw"></i>同步到微信
            <button class="layui-btn ml5" id="menu_clear"
                    layTips="清空公众号上所有的菜单，而不是下面表格里的数据,清空后可以再点同步，公众号上又会出现菜单的|3|#3595CC"><i class="fa fa-trash fa-fw"></i>清空线上菜单
            </button>
    </div>
    <div class="layui-collapse">
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">帮助</h2>
            <div class="layui-colla-content">
                <ul>
                    <li><span class="layui-badge layui-bg-green mr5">1</span>用于配置微信公众号显示的菜单，一级菜单最多3个，二级菜单最多5个</li>
                    <li><span class="layui-badge layui-bg-green mr5">2</span>添加一级菜单，名称，菜单级别为一级，选择类型</li>
                    <li><span class="layui-badge layui-bg-green mr5">3</span>选择类型：跳转URL，点击按扭两种操作</li>
                    <li>3-1.跳转URL，需要设置有效的url链接地址</li>
                    <li>3-2.点击按扭，需要选择关联事件，用于触发不同的操作，详细见：事件管理</li>
                    <li><span class="layui-badge layui-bg-green mr5">4</span>配置子菜单，在列表中找到一级菜单，点击操作列中的“添加子菜单”</li>
                    <li><span class="layui-badge layui-bg-green mr5">5</span>同步到微信，配置好的菜单，需要点击同步，即有效。</li>
                    <li><span class="layui-badge layui-bg-green mr5">6</span>清空线上菜单：可以在配置之前点击清理菜单，然后再点同步按扭即可</li>
                    <li class="layui-bg-orange"><span class="layui-badge mr5 layui-bg-green">7</span>删除：永久删除，慎重使用。</li>
                    <li><span class="layui-badge layui-bg-orange mr5">8</span>问题列表：</li>
                    <li style="background-color: #FFB800">8-1.微信公众号未更新最新菜单，请点击取消关注，然后再关注即可</li>
                    <li style="background-color: #FFB800">8-2.同步失败,点击右上角的清理缓存</li>
                    <li style="background-color: #FFB800">8-3.每次更改菜单，需要同步才生效</li>
                    <li><span class="layui-badge mr5">9</span>设置权重：请设置0-100之间的权重值，值越大，菜单排在越上面</li>
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
            {{# if(d.parent_id == 0){  }}
            <a class="layui-btn layui-btn-xs" lay-event="addChild">添加子菜单</a>
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
            var form = layui.form;
            //第一个实例
            table.render({
                elem: '#table-list'
                , height: 'auto'
                , method: 'post'
                , url: "{{route('menu.list')}}" //数据接口
                , page: true //开启分页
                , size: ''
                , cellMinWidth: 60
                , limit: 100
                , limits: ['100']
                , cols: [[ //表头
                    {field: 'id', title: 'ID', sort: true, width: 80}
                    , {field: 'name', title: '菜单名称', width: 300}
                    , {field: 'parent_id_format', title: '菜单级别', width: 120}
                    , {field: 'weight', title: '权重', width: 120, edit: 'text', sort: true}
                    , {field: 'is_use_format', title: '是否使用中', templet: '#switchTpl', sort: true, width: 120}
                    , {field: 'type_format', title: '菜单类型', width: 120}
                    , {field: 'update_format', title: '修改时间', sort: true, width: 120}
                    , {title: '操作', templet: '#barTable', unresize: true, width: 185}
                ]]
            });
            //单元格编辑
            table.on('edit(tableLay)', function (obj) {
                var value = parseInt(obj.value) //得到修改后的值
                    , data = obj.data //得到所在行所有键值
                    , field = obj.field; //得到字段
                if (value < 0 || value > 100) {
                    layer.msg('请填写0-100之间的值');
                    return false;
                }
                $.post("{{route('menu.weight')}}", {field: field, id: data.id, value: value}, function (rev) {
                    layer.msg(rev.msg);
                }, 'json');
            });
            //监听切换操作
            form.on('switch(change)', function (obj) {
                console.log(obj);
                var pp = {};
                pp.id = this.value;
                pp.value = obj.elem.checked ? 1 : 0;
                $.post("{{route('menu.change')}}", pp, function (rev) {
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
                    window.location.href = "{{route('menu.add')}}?id=" + data.id;
                } else if (obj.event === 'del') {
                    var tips = data.parent_id === 0 ? '您删除的是一级菜单,连同子菜单一起删除吗?' : '真的删除行么';
                    layer.confirm(tips, {icon: 2}, function (index) {
                        $.post("{{route('menu.del')}}", {id: data.id}, function (rev) {
                            if (rev.status === 0) {
                                obj.del();
                            }
                            layer.msg(rev.msg);
                        });
                        layer.close(index);
                    });
                } else if (obj.event === 'edit') {
                    window.location.href = "{{route('menu.edit')}}?id=" + data.id;
                }
            });

            $("#menu_clear").click(function () {
                var index = layer.load(2);
                $.getJSON("{{route('menu.clear')}}", function (rev) {
                    if (rev.status === 0) {
                        Msg.success(rev.msg);
                    } else {
                        Alert.alert(rev.msg);
                    }
                    layer.close(index);
                });
            });
            $("#menu_sync").click(function () {
                var index = layer.load(2);
                $.getJSON("{{route('menu.sync')}}", function (rev) {
                    if (rev.status === 0) {
                        Msg.success(rev.msg);
                    } else {
                        Alert.alert(rev.msg);
                    }
                    layer.close(index);
                });
            });
        });
    </script>
@stop