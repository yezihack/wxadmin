@extends('layout')
@section('title', $title)
@section('style')
    <style>
    </style>
@stop
@section('body')
    <blockquote class="layui-elem-quote">{{$title}}</blockquote>
    <div class="layui-form-item">
        <a class="layui-btn" href="{{route('user.add')}}">注册</a>
    </div>
    <div class="layui-collapse">
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">帮助</h2>
            <div class="layui-colla-content">
                <ul>
                    <li><span class="layui-badge">1</span>默认系统用户为:admin(名字可修改)</li>
                    <li><span class="layui-badge">2</span>帐号状态：可切换开启或关闭，开启表示帐号可以正常登陆，关闭表示帐号将无法登陆系统</li>
                    <li class="layui-bg-orange"><span class="layui-badge">3</span>删除：永久删除，慎重使用。但管理员帐号无法删除</li>
                    <li><span class="layui-badge">4</span>编辑可以修改登陆名称和密码</li>
                </ul>
            </div>
        </div>
    </div>
    <table id="table-list" lay-filter="tableLay"></table>
    @verbatim
        <script type="text/html" id="switchTpl">
            {{# if(d.is_admin != 1) { }}
            <input type="checkbox" name="" value="{{d.id}}" lay-skin="switch" lay-text="开启|禁用" lay-filter="change"
                    {{d.is_use == 1 ? 'checked' : '' }}>
            {{# } else { }}
            <span>管理员</span>
            {{# } }}
        </script>
        <script type="text/html" id="barTable">
            <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            {{# if(d.is_admin != 1 ) { }}
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
            {{# } }}
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
                , url: "{{route('user.list')}}" //数据接口
                , page: true //开启分页
                , size: 'lg'
                , cellMinWidth: 80
                , cols: [[ //表头
                    {field: 'id', title: 'ID', sort: true}
                    , {field: 'username', title: '用户名'}
                    , {field: 'username', title: '帐号状态', templet: '#switchTpl'}
                    , {field: 'update_format', title: '修改时间', sort: true}
                    , {title: '操作', templet: '#barTable', unresize: true, width: 178}
                ]]
            });
            //监听切换操作
            form.on('switch(change)', function (obj) {
                var pp = {};
                pp.id = this.value;
                pp.value = obj.elem.checked ? 1 : 0;
                $.post("{{route('user.change')}}", pp, function (rev) {
                    if (rev.status === 0) {
                        Msg.success(rev.msg);
                    } else {
                        Msg.warn(rev.msg);
                    }
                }, 'json');
            });
            table.on('tool(tableLay)', function (obj) {
                var data = obj.data;
                if (obj.event === 'detail') {
                    layer.alert(data.remark, {title: '备注信息'});
                } else if (obj.event === 'del') {
                    layer.confirm('真的删除行么', function (index) {
                        $.post("{{route('user.del')}}", {id: data.id}, function (rev) {
                            if (rev.status === 0) {
                                obj.del();
                                Msg.success(rev.msg);
                            } else {
                                Msg.error(rev.msg);
                            }
                        });
                        layer.close(index);
                    });
                } else if (obj.event === 'edit') {
                    window.location.href = "{{route('user.edit')}}?id=" + data.id;
                }
            });
        });
    </script>
@stop