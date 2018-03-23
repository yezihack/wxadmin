@extends('layout')
@section('title', $title)
@section('style')
    <style>
        .layui-badge {
            margin-right: 5px;
        }
    </style>
@stop
@section('body')
    <blockquote class="layui-elem-quote">{{$title}}
        <span class="layui-word-aux"></span>
    </blockquote>
    <h3 style="margin-bottom: 10px;"><span class="layui-badge-dot"></span>遇到错误时，记住点击右上角的“清理缓存”或"刷新"</h3>
    <div class="layui-collapse">
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">系统设置</h2>
            <div class="layui-colla-content layui-show">
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                    <legend>用户管理</legend>
                </fieldset>
                <ul>
                    <li><span class="layui-badge">1</span>默认系统用户为:admin(名字可修改)</li>
                    <li><span class="layui-badge">2</span>帐号状态：可切换开启或关闭，开启表示帐号可以正常登陆，关闭表示帐号将无法登陆系统</li>
                    <li class="layui-bg-orange"><span class="layui-badge">3</span>删除：永久删除，慎重使用。但管理员帐号无法删除</li>
                    <li><span class="layui-badge">4</span>编辑可以修改登陆名称和密码</li>
                </ul>
            </div>
        </div>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">微信设置</h2>
            <div class="layui-colla-content layui-show">
                <ul class="layui-timeline">
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <h3 class="layui-timeline-title">第一步：设置消息模板</h3>
                        </div>
                    </li>
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <h3 class="layui-timeline-title">第二步：设置事件管理</h3>
                        </div>
                    </li>
                    <li class="layui-timeline-item">
                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                        <div class="layui-timeline-content layui-text">
                            <h3 class="layui-timeline-title">第三步：设置菜单</h3>
                        </div>
                    </li>
                </ul>
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                    <legend>菜单配置</legend>
                </fieldset>
                <ul>
                    <li><span class="layui-badge layui-bg-green">1</span>用于配置微信公众号显示的菜单，一级菜单最多3个，二级菜单最多5个</li>
                    <li><span class="layui-badge layui-bg-green">2</span>添加一级菜单，名称，菜单级别为一级，选择类型</li>
                    <li><span class="layui-badge layui-bg-green">3</span>选择类型：跳转URL，点击按扭两种操作</li>
                    <li>3-1.跳转URL，需要设置有效的url链接地址</li>
                    <li>3-2.点击按扭，需要选择关联事件，用于触发不同的操作，详细见：事件管理</li>
                    <li><span class="layui-badge layui-bg-green">4</span>配置子菜单，在列表中找到一级菜单，点击操作列中的“添加子菜单”</li>
                    <li><span class="layui-badge layui-bg-green">5</span>同步到微信，配置好的菜单，需要点击同步，即有效。</li>
                    <li><span class="layui-badge layui-bg-green">6</span>清空线上菜单：可以在配置之前点击清理菜单，然后再点同步按扭即可</li>
                    <li class="layui-bg-orange"><span class="layui-badge layui-bg-green">7</span>删除：永久删除，慎重使用。</li>
                    <li><span class="layui-badge layui-bg-orange">8</span>问题列表：</li>
                    <li style="background-color: #FFB800">8-1.微信公众号未更新最新菜单，请点击取消关注，然后再关注即可</li>
                    <li style="background-color: #FFB800">8-2.同步失败,点击右上角的清理缓存</li>
                    <li style="background-color: #FFB800">8-3.每次更改菜单，需要同步才生效</li>
                </ul>
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                    <legend>消息管理</legend>
                </fieldset>
                <ul>
                    <li><span class="layui-badge layui-bg-cyan">1</span>消息类型分两种：文字消息，图文消息，配合事件管理使用，见：事件管理</li>
                    <li><span class="layui-badge layui-bg-cyan">2</span>文字类型：纯文本消息</li>
                    <li><span class="layui-badge layui-bg-cyan">3</span>图文消息：有图有文，可以自定义图片，链接，描述，一个图文可以添加7个子图文</li>
                    <li><span class="layui-badge layui-bg-cyan">4</span>消息名称：用于识别，微信里不显示</li>
                    <li><span class="layui-badge layui-bg-cyan">5</span>添加子图文：消息类型为图文消息，在操作列中点击“添加子图文”</li>
                    <li class="layui-bg-orange"><span class="layui-badge layui-bg-cyan">6</span>删除：永久删除，慎重使用。如果是删除主图文，子图文一并删除。
                    </li>
                    <li><span class="layui-badge layui-bg-cyan">7</span>编辑：可以修改已设置项</li>
                </ul>
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                    <legend>事件管理</legend>
                </fieldset>
                <ul>
                    <li><span class="layui-badge layui-bg-blue">1</span>事件管理：关键字事件，按扭事件，订阅消息</li>
                    <li><span class="layui-badge layui-bg-blue">2</span>关键字事件：设置关键字，如hello,在公众号里回复hello则返回相响应的事件，如果重名关键字，只响应最新的事件
                    </li>
                    <li><span class="layui-badge layui-bg-blue">3</span>按扭事件：需要设置识标，与菜单配合使用</li>
                    <li><span class="layui-badge layui-bg-blue">4</span>订阅消息：当新的用户关注时，响应事件</li>
                    <li><span class="layui-badge layui-bg-blue">5</span>状态：默认为正常使用，暂停则不会响应事件</li>
                    <li class="layui-bg-orange"><span class="layui-badge layui-bg-blue">6</span>删除：永久删除，慎重使用</li>
                    <li><span class="layui-badge layui-bg-blue">7</span>编辑：可以修改已设置项</li>
                    <li><span class="layui-badge layui-bg-blue">8</span>关联模板消息：即消息管理</li>
                </ul>
            </div>
        </div>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">渠道管理</h2>
            <div class="layui-colla-content layui-show">
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                    <legend>二维码管理</legend>
                </fieldset>
                <ul>
                    <li><span class="layui-badge">1</span>两种类型：临时二维码和永久二维码</li>
                    <li><span class="layui-badge">2</span>临时二维码：最多可设置30天有效期</li>
                    <li><span class="layui-badge">3</span>永久二维码：永久不过期，最多支持生成10万个二维码</li>
                    <li class="layui-bg-orange"><span class="layui-badge">4</span>删除：永久删除，慎重使用。</li>
                    <li><span class="layui-badge">5</span>查看统计：统计当前二维码关注数量，数据24小时更新一次</li>
                </ul>
            </div>
        </div>
    </div>

@stop
@section('script')
    <script>
        //注意：折叠面板 依赖 element 模块，否则无法进行功能性操作
        layui.use('element', function () {
            var element = layui.element;

            //…
        });
    </script>
@stop