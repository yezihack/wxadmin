<?php

namespace App\Http\Controllers;


use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function lists()
    {
        if ($this->isPost()) {
            $data = Users::orderBy('updated_at', 'desc')
                ->paginate($this->request->input('limit'));
            foreach ($data as $item) {
                $item->update_format = $item->updated_at->format('Y-m-d');
//                $item->update_format = $item->updated_at->diffForHumans();
            }
            $list    = $data->toArray();
            $content = [
                'code'  => 0,
                'msg'   => '',
                'count' => $list['total'],
                'data'  => $list['data'],
            ];
            return $content;
        }
        $title = '用户列表';
        return view('user.list', compact('title'));
    }

    public function del()
    {
        $id   = $this->request->input('id', 0);
        $item = Users::find($id);
        if (!$item) {
            return $this->setJson(10, '数据不存在');
        }
        if ($item->is_admin == 1) {
            return $this->setJson(12, '管理员帐号不允许操作');
        }
        $bool = $item->delete();
        if ($bool) {
            return $this->setJson(0, '操作成功');
        }
        return $this->setJson(400, '异常');
    }

    public function change()
    {
        $id     = $this->request->input('id', 0);
        $status = $this->request->input('value', 0);
        $item   = Users::find($id);
        if (!$item) {
            return $this->setJson(10, '数据不存在');
        }
        if (!in_array($status, [0, 1])) {
            return $this->setJson(11, '状态值异常');
        }
        if ($item->is_admin == 1) {
            return $this->setJson(12, '管理员帐号不允许操作');
        }
        $item->is_use = $status;
        $bool         = $item->save();
        if ($bool) {
            return $this->setJson(0, '操作成功');
        }
        return $this->setJson(400, '异常');
    }

    public function edit()
    {
        if ($this->isPost()) {
            $user_id   = $this->request->input('id', 0);
            $username  = $this->request->input('username', '');
            $password  = $this->request->input('password', '');
            $password2 = $this->request->input('password2', '');
            $user      = Users::find($user_id);
            if (!$user) {
                return $this->setJson(16, '用户不存在');
            }
            if ($user->username != $username) {
                $check = Users::where('id', '<>', $user->id)->where('username', $username)->first();
                if ($check) {
                    return $this->setJson(18, '用户名已被占用');
                }
                $user->username = $username;
            }
            if ($password || $password2) {
                if ($password == '' || $password2 == '') {
                    return $this->setJson(12, '密码不能为空');
                }
                if (Str::length($password) < 6) {
                    return $this->setJson(13, '密码不能少于6个字符');
                }
                if (Str::length($password) > 255) {
                    return $this->setJson(13, '密码最多255个字符');
                }
                if ($password != $password2) {
                    return $this->setJson(14, '两次输入密码不相同');
                }
                if (Hash::check($password, $user->password)) {
                    return $this->setJson(17, '不能使用旧密码');
                }
                $user->password = bcrypt($password);
            }
            $bool = $user->save();
            if ($bool) {
                return $this->setJson(0, '修改成功', route('user.list'));
            }
            return $this->setJson(15, '异常');
        }
        $title   = '编辑用户';
        $user_id = $this->request->input('id', 0);
        $user    = Users::find($user_id);
        return view('user.edit', compact('title', 'user'));
    }

    public function add()
    {
        if ($this->isPost()) {
            $username  = $this->request->input('username', '');
            $password  = $this->request->input('password', '');
            $password2 = $this->request->input('password2', '');
            $check     = Users::where('username', $username)->first();
            if ($check) {
                return $this->setJson(18, '用户名已被占用');
            }
            if ($password == '' || $password2 == '') {
                return $this->setJson(12, '密码不能为空');
            }
            if (Str::length($password) < 6) {
                return $this->setJson(13, '密码不能少于6个字符');
            }
            if (Str::length($password) > 255) {
                return $this->setJson(13, '密码最多255个字符');
            }
            if ($password != $password2) {
                return $this->setJson(14, '两次输入密码不相同');
            }
            $user           = new Users();
            $user->username = $username;
            $user->password = bcrypt($password);
            $bool           = $user->save();
            if ($bool) {
                return $this->setJson(0, '添加成功', route('user.list'));
            }
            return $this->setJson(15, '异常');
        }
        $title = '添加用户';
        return view('user.add', compact('title'));
    }

    public function pass()
    {
        if ($this->isPost()) {
            $password  = $this->request->input('password', '');
            $password2 = $this->request->input('password2', '');
            if ($password == '' || $password2 == '') {
                return $this->setJson(12, '密码不能为空');
            }
            if (Str::length($password) < 6) {
                return $this->setJson(13, '密码不能少于6个字符');
            }
            if (Str::length($password) > 255) {
                return $this->setJson(13, '密码最多255个字符');
            }
            if ($password != $password2) {
                return $this->setJson(14, '两次输入密码不相同');
            }
            $user           = new Users();
            $user->password = bcrypt($password);
            $bool           = $user->save();
            if ($bool) {
                return $this->setJson(0, '添加成功', route('user.list'));
            }
            return $this->setJson(15, '异常');
        }
        $title = '修改密码';
        return view('user.pass', compact('title'));
    }
}
