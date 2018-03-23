<?php

namespace App\Http\Controllers;

use App\Models\EventMsg;
use App\Models\Events;
use App\Models\Menus;
use App\Plugin\WxHelp;
use Illuminate\Support\Str;
use Mockery\Exception;

class MenuController extends Controller
{
    public function lists()
    {
        if ($this->isPost()) {
            $list    = Menus::get_list();
            $content = [
                'code'  => 0,
                'msg'   => '',
                'count' => count($list),
                'data'  => $list,
            ];
            return $content;
        }
        $title = '微信菜单列表';
        return view('menu.list', compact('title'));
    }

    public function edit()
    {
        $title       = '编辑微信菜单';
        $id          = $this->request->input('id', 0);
        $item        = Menus::find($id);
        $type_list   = Menus::$type_list;
        $parent_list = Menus::where('parent_id', 0)->pluck('name', 'id');
        $event_list  = Events::getJoinClickEvent();
        $pid         = 0;
        return view('menu.add', compact('title', 'type_list', 'parent_list', 'pid', 'event_list', 'item'));
    }

    public function add()
    {
        if ($this->isPost()) {
            $id          = $this->request->input('id', 0);
            $name        = $this->request->input('name', '');
            $parent_id   = $this->request->input('parent_id', '');
            $type        = $this->request->input('type', '');
            $view_value  = $this->request->input('view_value', '');
            $click_value = $this->request->input('click_value', '');
            if ($id == 0) {
                $check = Menus::where('name', $name)->first();
                if ($check) {
                    return $this->setJson(18, '菜名名称已被占用');
                }
            }
            if ($type == '') {
                return $this->setJson(20, '请选择类型');
            }
            if ($type && !in_array($type, array_keys(Menus::$type_list))) {
                return $this->setJson(1, '菜单非法类型');
            }
            if ($parent_id == 0) {
                if (Str::length($name) > 16)
                    return $this->setJson(13, '菜单标题，不超过16个字节');
                $count = Menus::where('parent_id', 0)->where('is_use', 1)->count();
                if ($count >= 3) {
                    return $this->setJson(14, '一级菜单最多开启3个');
                }
            }
            if ($parent_id > 0) {
                if (Str::length($name) > 60)
                    return $this->setJson(13, '子菜单不超过60个字节');
                $count = Menus::where('parent_id', '=', $parent_id)->where('id', '<>', $id)->where('is_use', 1)->count();
                if ($count >= 5) {
                    return $this->setJson(14, '二级菜单最多开启5个');
                }
            }
            if ($type == 'view') {
                if ($view_value == '')
                    return $this->setJson(16, '请填写链接地址');
                if (Str::length($view_value) > 1024)
                    return $this->setJson(13, '网页链接，用户点击菜单可打开链接，不超过1024字节');
                $value = $view_value;
            }
            if ($type == 'click') {
                if ($click_value == '') {
                    return $this->setJson(15, '选择关联事件');
                }
                $value = $click_value;
            }
            if ($id > 0) {
                $msg  = '编辑成功';
                $item = Menus::find($id);
            } else {
                $msg  = '添加成功';
                $item = new Menus();
            }

            $item->name      = $name;
            $item->parent_id = $parent_id;
            $item->type      = $type;
            $item->value     = $value;
            $bool            = $item->save();
            if ($bool) {
                return $this->setJson(0, $msg, route('menu.list'));
            }
            return $this->setJson(15, '异常');
        }
        $title       = '添加微信菜单';
        $type_list   = Menus::$type_list;
        $parent_list = Menus::where('parent_id', 0)->pluck('name', 'id');
        $event_list  = Events::getJoinClickEvent();
        $pid         = $this->request->input('id', 0);
        return view('menu.add', compact('title', 'type_list', 'parent_list', 'pid', 'event_list'));
    }

    public function query()
    {
        $result = WxHelp::queryMenu();
        mydump($result);
    }

    public function weight()
    {
        $id    = $this->request->input('id', 0);
        $field = $this->request->input('field', '');
        $value = $this->request->input('value', '');
        $item  = Menus::find($id);
        if (!$item) {
            return $this->setJson(10, '数据不存在');
        }
        $item->$field = $value;
        $bool         = $item->save();
        if ($bool) {
            return $this->setJson(0, '操作成功');
        }
        return $this->setJson(400, '异常');
    }

    public function del()
    {
        $id   = $this->request->input('id', 0);
        $item = Menus::find($id);
        if (!$item) {
            return $this->setJson(10, '数据不存在');
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
        $item   = Menus::find($id);
        if (!$item) {
            return $this->setJson(10, '数据不存在');
        }
        if (!in_array($status, [0, 1])) {
            return $this->setJson(11, '状态值异常');
        }
        if ($item->parent_id == 0 && $status == 1) {
            $count = Menus::where('parent_id', 0)->where('is_use', 1)->count();
            if ($count >= 3) {
                return $this->setJson(400, '一级菜单最多开启3个');
            }
        }
        if ($item->parent_id > 0 && $status == 1) {
            $count = Menus::where('parent_id', '', $item->parent_id)->where('is_use', 1)->count();
            if ($count >= 5) {
                return $this->setJson(14, '二级菜单最多开启5个');
            }
        }
        $item->is_use = $status;
        $bool         = $item->save();
        if ($bool) {
            return $this->setJson(0, '操作成功');
        }
        return $this->setJson(400, '异常');
    }

    /**
     * 同步菜单
     * @return $this|Response
     */
    public function sync()
    {
        $data = Menus::where('is_use', 1)->orderBy('weight', 'desc')->get();
        $list = [];
        foreach ($data as $item) {
            if ($item->parent_id == 0) {
                $tmp = [
                    'name'       => $item->name,
                    'sub_button' => [],
                ];
                foreach ($data as $child) {
                    if ($child->parent_id > 0 && $item->id == $child->parent_id) {
                        $childTmp = [
                            'type' => $child->type,
                            'name' => $child->name,
                        ];
                        switch ($child->type) {
                            case 'click':
                                $childTmp['key'] = $child->value;
                                break;
                            case 'view':
                                $childTmp['url'] = $child->value;
                        }
                        $tmp['sub_button'][] = $childTmp;
                    }
                }
                $list['button'][] = $tmp;
            }
        }
        try {
            $json      = json_encode($list, JSON_UNESCAPED_UNICODE);
            $resultObj = WxHelp::setMenu($json);
            if ($resultObj->errcode == 0) {
                return $this->setJson(0, '同步成功');
            }
            return $this->setJson(400, '同步失败，错误号：' . $resultObj->errcode . ',详情: ' . $resultObj->errmsg);
        } catch (Exception $e) {
            mylog($e->getMessage(), 'menu-try');
            return $this->setJson(404, $e->getMessage());
        }
    }

    public function clear()
    {
        $resultObj = WxHelp::clearMenu();
        if ($resultObj->errcode == 0) {
            return $this->setJson(0, '删除成功');
        }
        return $this->setJson(400, '删除失败，错误号：' . $resultObj->errcode . ',详情: ' . $resultObj->errmsg);
    }
}
