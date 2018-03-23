@extends('layout')
@section('title', $title)
@section('body')
    <blockquote class="layui-elem-quote">{{$title}}</blockquote>
    <form method="get" action="{{route('qr.download')}}">
        <div class="layui-form-item">
            <label class="layui-form-label">统计:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="start_date" name="start_date" placeholder="开始日期">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="end_date" name="end_date" placeholder="结束日期">
            </div>
            <div class="layui-input-inline">
                <button class="layui-btn" id="search">下载统计数据</button>
            </div>
        </div>
    </form>
    <div class="layui-form-item">
        <a class="layui-btn layui-btn-warm" href="{{route('qr.download')}}">今日统计</a>
        <a layTips="添加生成渠道推广二维码图片|3|#3595CC" class="layui-btn ml5" href="{{route('qr.add')}}"><i
                    class="fa fa-plus-circle fa-fw"></i>添加渠道二维码</a>
    </div>
    <div class="layui-collapse">
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">帮助</h2>
            <div class="layui-colla-content layui-show">
                <ul>
                    <li><span class="layui-badge mlr5">1</span>缓存时间：24小时更新一次</li>
                    <li><span class="layui-badge mlr5">2</span>如何即时查看最新数据：点击右上角的“清理缓存”</li>
                    <li><span class="layui-badge mlr5">3</span>修改数据：带<i class="fa fa-edit fa-fw"></i>图标，表示可以直接修改数据，鼠标点击单元格
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <table id="table-list" lay-filter="tableLay"></table>
    @verbatim
        <script type="text/html" id="switchTpl">
            <input type="checkbox" value="{{d.id}}" lay-skin="switch" lay-text="使用|禁用"
                   lay-filter="change" {{d.is_use== 1 ? 'checked' : '' }}>
        </script>
        <script type="text/html" id="hrefTpl">
            <a class="layui-btn layui-btn-xs" target="_blank" lay-event="look">查看</a>
            <a class="layui-btn layui-btn-xs" target="_blank" lay-event="download">下载(大)</a>
        </script>
        <script type="text/html" id="barTable">
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
            <a class="layui-btn layui-btn-xs" lay-event="stat">查看统计</a>
        </script>
    @endverbatim
@stop
@section('script')
    <script>
        $(function () {
            $("#search").click(function () {

            });
        });
        layui.use(['table', 'form', 'element', 'laydate'], function () {
            var element = layui.element;
            var table = layui.table;
            var form = layui.form;
            var laydate = layui.laydate;
            //日期范围
            laydate.render({
                elem: '#start_date'
            });
            laydate.render({
                elem: '#end_date'
            });
            //第一个实例
            table.render({
                elem: '#table-list'
                , height: 'auto'
                , method: 'post'
                , url: "{{route('qr.list')}}" //数据接口
                , page: true //开启分页
                , size: ''
                , cellMinWidth: 60
                , limit: 15
                , limits: [15, 30]
                , cols: [[ //表头
                    {field: 'id', title: 'ID', sort: true}
                    , {field: 'remark', title: '<i class="fa fa-edit fa-fw"></i>渠道名称', edit: 'text'}
                    , {field: 'type_name', title: '二维码类型'}
                    , {field: 'url', title: '二维码链接', templet: '#hrefTpl'}
                    , {field: 'sy_day', title: '有效时间'}
                    , {field: 'is_use', title: '状态切换', templet: '#switchTpl'}
                    , {field: 'update_format', title: '修改时间', sort: true}
                    , {title: '操作', templet: '#barTable', unresize: true, width: 180}
                ]]
            });

            //监听切换操作
            form.on('switch(change)', function (obj) {
                console.log(obj);
                var pp = {};
                pp.id = this.value;
                pp.value = obj.elem.checked ? 1 : 0;
                $.post("{{route('qr.change')}}", pp, function (rev) {
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
            table.on('edit(tableLay)', function (obj) {
                var field = obj.field;
                var val = obj.value;
                $.post('{{route('qr.update')}}', {id: obj.data.id, field: field, val: val}, function (rev) {
                    if (rev.status === 0) {
                        Msg.success(rev.msg);
                    } else {
                        Alert.alert(rev.msg);
                    }

                });
            });
            table.on('tool(tableLay)', function (obj) {
                console.log(obj);
                var data = obj.data;
                switch (obj.event) {
                    case "del":
                        var tips = '真的要删除吗？';
                        layer.confirm(tips, {icon: 2}, function (index) {
                            $.post("{{route('qr.del')}}", {id: data.id}, function (rev) {
                                if (rev.status === 0) {
                                    obj.del();
                                }
                                layer.msg(rev.msg);
                            });
                            layer.close(index);
                        });
                        break;
                    case "look":
                        $.getJSON('{{route('qr.src')}}', {id: data.id}, function (rev) {
                            if (rev.status === 0) {
                                layer.open({
                                    type: 1,
                                    title: '二维码',
                                    skin: 'layui-layer-rim', //加上边框
                                    offset: '50px',
                                    maxWidth: '440px',
                                    // area: ['420px', '240px'], //宽高
                                    content: '<img src="' + rev.data + '">'
                                });
                            } else {
                                Alert.alert(rev.msg);
                            }
                        });
                        break;
                    case "download":
                        window.location.href = "{{route('qr.downqr')}}?url=" + data.url;
                        break;
                    case 'stat':
                        layer.open({
                            type: 2,
                            area: ['90%', '600px'],
                            content: ["{{route('qr.stat')}}?id=" + data.id, 'no']
                        });
                        break;
                    default:
                        break;
                }
            });
        });
    </script>
@stop