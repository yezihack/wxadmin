<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="" media="screen"/>
    <link rel="stylesheet" href="{{asset('statics/plugins/layui/css/layui.css')}}">
    <link rel="stylesheet" href="{{asset('statics/plugins/layer/skin/default/layer.css')}}">
    <link rel="stylesheet" href="{{asset('statics/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('statics/css/main.css')}}">
    @yield('style')
</head>
<body>
@yield('body')
<script src="{{asset('statics/js/jquery-3.2.1.min.js')}}?v={{time()}}"></script>
<script src="{{asset('statics/plugins/layer/layer.js')}}?v={{time()}}"></script>
<script src="{{asset('statics/plugins/layui/layui.js')}}?v={{time()}}"></script>
<script src="{{asset('common/js/whole/utils.js')}}?v={{time()}}"></script>
<script src="{{asset('common/js/whole/cyLayer.js')}}?v={{time()}}"></script>
<script src="{{asset('statics/js/vue.min.js')}}"></script>
<script src="{{asset('statics/js/main.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    });
</script>
@yield('script')
</body>
</html>
