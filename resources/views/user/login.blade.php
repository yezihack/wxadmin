@extends('layout')
@section('title', '登录--后台管理')
@section('style')
    <link rel="stylesheet" href="{{asset('statics/css/login.css')}}" media="all" />
@stop
@section('body')
<div class="video_mask"></div>
<div class="login">
    <h1>管理员登录</h1>
    <form class="layui-form">
        <div class="layui-form-item">
            <input class="layui-input" name="username" placeholder="用户名" value="" lay-verify="required"  lay-verType="tips" type="text" autocomplete="off">
        </div>
        <div class="layui-form-item">
            <input class="layui-input" name="password" placeholder="密码" value=""  lay-verify="required"   lay-verType="tips"  type="password" autocomplete="off">
        </div>
        <div class="layui-form-item form_code">
            <input class="layui-input" style="width: 140px;" name="captcha" placeholder="验证码" lay-verify="required"   lay-verType="tips"  type="text" autocomplete="off">
            <div class="code"><img id="captcha" src="{{captcha_src()}}" width="116" height="36" onclick="this.src='{{captcha_src()}}&'+Math.random()"></div>
        </div>
        <button class="layui-btn login_btn" lay-submit="" lay-filter="login">登录</button>
    </form>
</div>
@stop
@section('script')
<script type="text/javascript" src="{{asset('statics/js/login.js')}}?v={{time()}}"></script>
@stop
