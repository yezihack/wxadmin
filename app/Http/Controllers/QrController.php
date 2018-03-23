<?php

namespace App\Http\Controllers;

use App\Models\QrCodes;
use App\Models\QrStat;
use App\Plugin\PHPExcelHelp;
use App\Plugin\WxHelp;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    public function lists()
    {
        if ($this->isPost()) {
            $data = QrCodes::orderBy('updated_at', 'desc')
                ->paginate($this->request->input('limit'));
            foreach ($data as $item) {
                $item->type_name     = QrCodes::$type_list[$item->type];
                $item->update_format = $item->updated_at->format('Y-m-d');
//                $item->update_format = $item->updated_at->diffForHumans();
                if ($item->type == 'QR_STR_SCENE') {
                    $weilai = strtotime($item->created_at) + $item->day * 24 * 3600;
                    if (time() > $weilai) {
                        $item->sy_day = '已过期';
                    } else {
                        $diff         = $weilai - time();
                        $item->sy_day = ceil($diff / 24 / 3600) . '天';
                    }
                } else {
                    $item->sy_day = '永久';
                }
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
        $title = '渠道二维码列表';
        return view('qr.list', compact('title'));
    }

    /**
     * 下载二维码
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downqr()
    {
        if (!file_exists(public_path('qrcodes'))) {
            mkdir(public_path('qrcodes'));
        }
        $size = 1000;
        $url  = $this->request->input('url');
        $name = trim(strrchr($url, '/'), '/') . '.png';
        $file = public_path('qrcodes/' . $name);
        if (!is_file($file)) {
            QrCode::format('png')->size($size)->generate($url, public_path('qrcodes/' . $name));
        }
        return $this->response->download($file, $name);
    }

    /**
     * 下载
     */
    public function download()
    {
        $start_date = $this->request->input('start_date', date('Y-m-d'));
        $end_date   = $this->request->input('end_date', date('Y-m-d'));

        if (empty($start_date)) {
            $start_date = date('Y-m-d');
        }
        if (empty($end_date)) {
            $end_date = date('Y-m-d');
        }
        $start  = $start_date . ' 00:00:00';
        $end    = $end_date . ' 23:59:59';
        $data   = QrStat::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->orderBy('created_at')
            ->get();
        $header = [
            '渠道名称', '时间', '关注量'
        ];
        $list   = [];
        if (count($data) > 0) {
            $date_list = [];
            foreach ($data as $item) {
                $qr_id       = $item->qr_id;
                $date_format = $item->created_at->format('Y-m-d');
                if (isset($date_list[$date_format][$qr_id])) {
                    $date_list[$date_format][$qr_id]['cnt'] += 1;
                } else {
                    $date_list[$date_format][$qr_id] = [
                        'qr_id' => $item->qr_id,
                        'cnt'   => 1,
                        'date'  => $item->created_at->format('Y-m-d H:i:s'),
                    ];
                }
            }
            foreach ($date_list as $date => $item) {
                foreach ($item as $child) {
                    $qr = QrCodes::find($child['qr_id']);
                    if ($qr) {
                        $list[] = [$qr->remark, $child['date'], $child['cnt']];
                    }
                }
            }
        }
        $excel = new PHPExcelHelp();
        $excel->report($header, $list);
    }


    public function stat()
    {
        $id = $this->request->input('id', 0);
        if ($this->isPost()) {
            $data = QrStat::getData($id);
            return $this->setJson(0, 'ok', $data);
        }
        $title = '统计';
        return view('qr.stat', compact('title', 'id'));
    }

    public function update()
    {
        $id    = $this->request->input('id', 0);
        $field = $this->request->input('field', '');
        $val   = $this->request->input('val', '');
        $item  = QrCodes::find($id);
        if (!$item) {
            return $this->setJson(4, '数据不存在');
        }
        $item->remark = $val;
        $bool         = $item->save();
        if ($bool) {
            return $this->setJson(0, '更新成功');
        }
        return $this->setJson(400, '操作失败');
    }

    public function add()
    {
        if ($this->isPost()) {
            $id        = $this->request->input('id', 0);
            $type      = $this->request->input('type');
            $day       = $this->request->input('day', 0);
            $scene_str = $this->request->input('scene_str');
            $remark    = $this->request->input('remark');
            if ($type == '') {
                return $this->setJson(1, '选择二维码类型');
            }
            if ($remark == '') {
                return $this->setJson(2, '请输入备注说明');
            }
            if ($scene_str == '') {
                return $this->setJson(2, '请输入scene_str');
            }
            if ($id > 0) {
                $msg          = '编辑成功';
                $item         = QrCodes::find($id);
                $item->remark = $remark;
            } else {
                $msg             = '生成成功';
                $item            = new QrCodes();
                $item->scene_str = $scene_str;
                $item->is_use    = 1;
                $item->day       = $day;
                $qrObj           = WxHelp::qrCode($type, $item->scene_str, $day);
                if (isset($qrObj->ticket)) {
                    $item->ticket = $qrObj->ticket;
                    $item->url    = $qrObj->url;
                    $item->remark = $remark;
                    $check        = WxHelp::qrImage($item->ticket);
                    if ($check['status'] != 0) {
                        return $this->setJson(5, $check['msg']);
                    }
                } else {
                    return $this->setJson(4, '生成失败,试试清理缓存');
                }
            }
            $item->type = $type;
            $bool       = $item->save();
            if ($bool) {
                return $this->setJson(0, $msg);
            }
            return $this->setJson(15, '异常');
        }
        $title     = '添加二维码';
        $type_list = QrCodes::$type_list;
        $scene_str = QrCodes::getOnlyStr();
        return view('qr.add', compact('title', 'type_list', 'scene_str'));
    }

    public function src()
    {
        $id   = $this->request->input('id');
        $item = QrCodes::find($id);
        if (!$item) {
            return $this->setJson(10, '数据不存在');
        }
        $image = public_path('qr') . '/' . $item->ticket . '.png';
        if (!is_file($image)) {
            return $this->setJson(1, '图片QR不存在');
        }
        $src = asset('qr/' . $item->ticket . '.png');
        return $this->setJson(0, '', $src);
    }

    public function del()
    {
        $id   = $this->request->input('id', 0);
        $item = QrCodes::find($id);
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
        $item   = QrCodes::find($id);
        if (!$item) {
            return $this->setJson(10, '数据不存在');
        }
        if (!in_array($status, [0, 1])) {
            return $this->setJson(11, '状态值异常');
        }
        $item->is_use = $status;
        $bool         = $item->save();
        if ($bool) {
            return $this->setJson(0, '操作成功');
        }
        return $this->setJson(400, '异常');
    }
}
