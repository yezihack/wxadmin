<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function view()
    {
        if ($this->isPost()) {
            $username = $this->request->input('username', '');
            $password = $this->request->input('password', '');
            $captcha  = $this->request->input('captcha', '');
            if ($username == '') {
                return $this->setJson(10, '用户名称不能为空');
            }
            if (Str::length($username) < 3) {
                return $this->setJson(11, '用户名不能少于3个字符');
            }
            if (Str::length($username) > 50) {
                return $this->setJson(11, '用户名最多50个字符');
            }
            if ($password == '') {
                return $this->setJson(12, '密码不能为空');
            }
            if ($captcha == '') {
                return $this->setJson(1, '请输入验证码');
            }
            if (!captcha_check($captcha)) {
                return $this->setJson(1, '验证码输入错误');
            }
            //判断是否重复
            $user = Users::where('username', $username)->first();
            if (!$user) {
                return $this->setJson(100, '用户名或密码不正确');
            }

            if (!Hash::check($password, $user->password)) {
                return $this->setJson(13, '用户名或密码不正确');
            }
            $user->login_count++;
            $user->last_ip = $this->request->getClientIp();
            $bool          = $user->save();
            if ($bool) {
                Users::setSession($user);
                $to       = route('home');
                if (strpos(url()->previous(), 'login') === false) {
                    $to = url()->previous();
                }
                return $this->setJson(0, '', $to);
            }
            return $this->setJson(15, '异常');
        }
        return view('user.login');
    }


    /**
     * 退出
     * @return string
     */
    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }
}
