<?php

namespace App\Http\Controllers;

use App\Models\EventMsg;
use App\Models\Events;

class EventController extends Controller
{
    public function lists()
    {
        if ($this->isPost()) {
            $data = Events::orderBy('updated_at', 'desc')
                ->paginate($this->request->input('limit'));
            foreach ($data as $item) {
                $item->msg_name      = EventMsg::where('id', $item->msg_id)->value('name');
                $item->type_name     = Events::$type_list[$item->type];
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
        $title = '事件管理列表';
        return view('event.list', compact('title'));
    }

    public function edit()
    {
        if ($this->isPost()) {
            return $this->setJson(15, '异常');
        }
        $title     = '修改事件';
        $id        = $this->request->input('id', 0);
        $item      = Events::find($id);
        $type_list = Events::$type_list;
        $type      = $this->request->input('type');
        $msg_list  = EventMsg::where('parent_id', 0)->pluck('name', 'id');
        return view('event.add', compact('title', 'type_list', 'type', 'msg_list', 'item'));
    }

    public function add()
    {
        if ($this->isPost()) {
            $id      = $this->request->input('id', 0);
            $type    = $this->request->input('type', '');
            $content = trim($this->request->input('content', ''));
            $msg_id  = intval($this->request->input('msg_id', 0));
            if ($type == '') {
                return $this->setJson(1, '请选择事件类型');
            }
            if (in_array($type, ['click', 'keywords'])) {
                if ($content == '') {
                    return $this->setJson(2, '关键字或标识不能为空');
                }
                if ($type == 'click') {
                    if (!preg_match('/[a-zA-Z0-9]+/i', $content)) {
                        return $this->setJson(3, 'click按扭事件只允许字母和数字做为标识');
                    }

                }
            }
            if (in_array($type, ['subscribe', 'unsubscribe'])) {
                $count = Events::where('type', $type)->count();
                if ($count >= 1) {
                    $msg = Events::$type_list[$type];
                    return $this->setJson(3, $msg . '只允许添加一个');
                }
            }
            if ($msg_id <= 0) {
                return $this->setJson(3, '选择关联的消息模板');
            }
            if ($id > 0) {
                $msg  = '修改成功';
                $item = Events::find($id);
            } else {
                $msg  = '添加成功';
                $item = new Events();
                //检查click，content不能得复
                if($type == 'click') {
                    $check = Events::where('type', 'click')->where('content', $content)->count();
                    if($check > 0){
                        return $this->setJson(8, '标识已经被占用');
                    }
                }

            }
            $item->type    = $type;
            $item->msg_id  = $msg_id;
            $item->content = $content;
            $item->is_use  = 1;
            $bool          = $item->save();
            if ($bool) {
                return $this->setJson(0, $msg, route('event.list'));
            }
            return $this->setJson(15, '异常');
        }
        $title     = '添加事件';
        $type_list = Events::$type_list;
        $type      = $this->request->input('type');
        $msg_list  = EventMsg::where('parent_id', 0)->pluck('name', 'id');
        return view('event.add', compact('title', 'type_list', 'type', 'msg_list'));
    }

    public function del()
    {
        $id   = $this->request->input('id', 0);
        $item = Events::find($id);
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
        $item   = Events::find($id);
        if (!$item) {
            return $this->setJson(10, '数据不存在');
        }
        if (!in_array($status, [0, 1])) {
            return $this->setJson(1, '只允许0，1');
        }
        $item->is_use = $status;
        $bool         = $item->save();
        if ($bool) {
            return $this->setJson(0, '操作成功');
        }
        return $this->setJson(400, '异常');
    }
}
