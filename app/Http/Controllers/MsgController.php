<?php

namespace App\Http\Controllers;

use App\Models\EventMsg;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MsgController extends Controller
{
    public function lists()
    {
        if ($this->isPost()) {
            $data = EventMsg::where('parent_id', '=', 0)
                ->paginate($this->request->input('limit'));
            foreach ($data as $item) {
                $item->msg_type_name = EventType::where('code', $item->event_code)->value('name');
//                $item->update_format = $item->updated_at->diffForHumans();
                $item->update_format = $item->updated_at->format('Y-m-d');
            }
            $list    = $data->toArray();
            $data = [];
            if($list['data']) {
                foreach ($list['data'] as $key => $item) {
                    $data[] = $item;
                    $childs = EventMsg::where('parent_id', $item['id'])->get();
                    if(count($childs) > 0) {
                        foreach ($childs as $child) {
                            $child->msg_type_name = '├──子' . EventType::where('code', $child->event_code)->value('name');
                            $child->update_format = $child->updated_at->format('Y-m-d');
                            $data[] = $child->toArray();
                        }
                    }
                }
            }
            $content = [
                'code'  => 0,
                'msg'   => '',
                'count' => $list['total'],
                'data'  => $data,
            ];
            return $content;
        }
        $title = '消息模板列表';
        return view('msg.list', compact('title'));
    }

    public function edit()
    {
        $title     = '编辑消息模板';
        $id        = $this->request->input('id', 0);
        $item      = EventMsg::find($id);
        $type_list = EventType::pluck('name', 'code');
        $pid       = 0;
        return view('msg.add', compact('title', 'item', 'type_list', 'pid'));
    }

    public function add()
    {
        if ($this->isPost()) {
            $id            = $this->request->input('id', 0);
            $name          = $this->request->input('name');
            $msg_type_code = $this->request->input('msg_type_code', 0);
            $title         = trim($this->request->input('title', ''));
            $url           = trim($this->request->input('url', ''));
            $desc          = trim($this->request->input('desc', ''));
            $parent_id     = intval($this->request->input('parent_id', 0));
            $pic_url       = trim($this->request->input('pic_url', ''));
            if (empty($msg_type_code)) {
                return $this->setJson(1, '选择消息类型');
            }
            switch ($msg_type_code) {
                case 'text'://文本消息
                    if ($desc == '') {
                        return $this->setJson(3, '请输入消息描述');
                    }
                    $pic_url = '';
                    break;
                case 'news'://图文消息事件
                    if (empty($title)) {
                        return $this->setJson(3, '请输入消息标题');
                    }
                    if (empty($url)) {
                        return $this->setJson(3, '请输入消息链接');
                    }
                    if (!filter_var($url, FILTER_VALIDATE_URL)) {
                        return $this->setJson(4, '填写的链接地址不合法');
                    }
                    if (empty($pic_url)) {
                        return $this->setJson(3, '请输入图片链接');
                    }
                    if (empty($desc)) {
                        return $this->setJson(3, '请输入消息描述');
                    }
                    $desc = strip_tags($desc);
                    break;
                case 'link'://链接消息
                    if (empty($title)) {
                        return $this->setJson(3, '请输入消息标题');
                    }
                    if (empty($url)) {
                        return $this->setJson(3, '请输入消息链接');
                    }
                    if (empty($desc)) {
                        return $this->setJson(3, '请输入消息描述');
                    }
                    $pic_url = '';
                    break;
                default:
                    break;
            }
            if ($id > 0) {
                $msg  = '编辑成功';
                $item = EventMsg::find($id);
            } else {
                $msg  = '添加成功';
                $item = new EventMsg();
            }
            //判断子图文是否超8个
            if ($parent_id > 0) {
                $count = EventMsg::where('parent_id', $parent_id)->count();
                if ($count >= 7) {
                    return $this->setJson(5, '图文消息最多8个');
                }
            }
            $desc             = str_replace('target="_blank"', '', $desc);
            $desc             = str_replace('target="_self"', '', $desc);
            $item->name       = $name;
            $item->event_code = $msg_type_code;
            $item->title      = $title;
            $item->url        = $url;
            $item->desc       = $desc;
            $item->parent_id  = $parent_id;
            $item->pic_url    = $pic_url;
            $bool             = $item->save();
            if ($bool) {
                return $this->setJson(0, $msg, route('msg.list'));
            }
            return $this->setJson(15, '异常');
        }
        $title     = '添加事件消息';
        $type_list = EventType::pluck('name', 'code');
        $pid       = $this->request->input('id', 0);
        return view('msg.add', compact('title', 'type_list', 'pid'));
    }

    public function del()
    {
        $id   = $this->request->input('id', 0);
        $item = EventMsg::find($id);
        if (!$item) {
            return $this->setJson(10, '数据不存在');
        }
        $bool = $item->delete();
        if ($bool) {
            if ($item->parent_id == 0) {
                EventMsg::where('parent_id', $item->id)->delete();
                return $this->setJson(0, '操作成功', '刷新');
            }
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
}
