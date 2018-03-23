<?php

namespace App\Http\Controllers;

use App\Models\QrStat;
use App\Plugin\WxHelp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CommonController extends Controller
{
    public function test()
    {
        $a = mylog($_SERVER, 'server');
        dump($a);
//        $s = QrStat::getData(1);
//        dump($s);
        $i = 0;
//        do {
//            $bool = QrStat::create([
//                'qr_id'      => mt_rand(5, 9),
//                'openid'     => str_random(),
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ]);
//            dump($bool);
//            $i++;
//        } while ($i < 100);

//        $browser_total_raw = DB::raw('count(*) as total');
//        $user_info = QrStat::getQuery()
//            ->select('*', $browser_total_raw)
//            ->groupBy('created_at')
//            ->get();
//        dd($user_info);
//        if (!file_exists(public_path('qrcodes'))) {
//            mkdir(public_path('qrcodes'));
//        }
//        $size = 1000;
//        $url  = 'http://weixin.qq.com/q/02N1pyl6sXfk310000w07Q';
//        $name = trim(strrchr($url, '/'), '/') . '.png';
//        $file  = public_path('qrcodes/' . $name);
//        if (!is_file($file)) {
//            QrCode::format('png')->size($size)->generate($url, public_path('qrcodes/' . $name));
//        }
//        return $this->response->download($file, $name);

    }

    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = '后台管理';
        return view('main', compact('title'));
    }

    /**
     * 获取菜单
     * @return mixed
     */
    public function menu()
    {
        return config('nav');
    }

    public function welcome()
    {
        $title = '使用说明';
        return view('common.welcome', compact('title'));
    }

    /**
     * 上传文件
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $data     = [
            'code' => 10,
            'msg'  => '',
            'data' => ''
        ];
        $allowExt = ["jpg", "png"];
        $dirPath  = public_path('uploads/' . date('Ym'));
        //如果上传的是图片
        $file = $this->request->file('file');
        //如果目标目录不能创建
        if (!is_dir($dirPath) && !mkdir($dirPath)) {
            $data['code'] = 1;
            $data['msg']  = '上传目录没有创建文件夹权限';
            return $this->response->json($data);
        }
        //如果目标目录没有写入权限
        if (is_dir($dirPath) && !is_writable($dirPath)) {
            $data['code'] = 2;
            $data['msg']  = '上传目录没有写入权限';
            return $this->response->json($data);
        }
        //校验文件
        if (isset($file) && $file->isValid()) {
            $ext = $file->getClientOriginalExtension(); //上传文件的后缀
            //判断是否是图片
            if (empty($ext) or in_array(strtolower($ext), $allowExt) === false) {
                $data['code'] = 0;
                $data['msg']  = '不允许的文件类型';
                return $this->response->json($data);
            }
            //生成文件名
            $fileName = uniqid() . '_' . dechex(microtime(true)) . '.' . $ext;
            try {
                $path         = $file->move('uploads/' . date('Ym'), $fileName);
                $webPath      = '/' . $path->getPath() . '/' . $fileName;
                $data['code'] = 0;
                $data['msg']  = 'ok';
                $data['data'] = url($webPath);
                return $this->setJson(0, 'ok', url($webPath));
            } catch (\Exception $ex) {
                $data['code'] = 400;
                $data['msg']  = $ex->getMessage();
                return $this->response->json($data);
            }
        }
        return $this->response->json($data);
    }

    public function c()
    {
        mylog(1, 1, 'all');
        Cache::flush();
        if ($this->request->ajax()) {
            return $this->setJson(0, '清理完毕');
        }
        dd('ok');
    }

    public function db107()
    {
        return;
        DB::beginTransaction();
        try {
            $data = DB::select('select * from plugin_weixin_dev_qrcode_scan');
            echo "共" . count($data);
            $i = $e = $p = 0;
            foreach ($data as $item) {
                $date = date('Y-m-d H:i:s', $item->createdate);
                if (!is_numeric($item->scene_str)) {
                    $p++;
                    continue;
                }
                $bool = QrStat::create([
                    'qr_id'      => $item->scene_str,
                    'openid'     => $item->open_id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
                if ($bool) {
                    $i++;
                } else {
                    $e++;
                }
            }
            echo '<br/>成功' . $i . ',失败:' . $e . ',忽略:' . $p;
            if ($e > 0) {
                echo "回滚了";
                DB::rollback();
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            echo "回滚了";
            dump($ex->getMessage());
        }
    }

    /**
     * 获取access-token
     * @return $this|Response
     */
    public function getAccessToken()
    {
        $content = [
            'status' => 100,
            'msg' => '',
            'access_token' => '',
        ];
        $token = WxHelp::getAccessToken();
        Session::put();
        if ($token == '') {
            $content['status'] = 100;
            $content['msg'] = 'please refresh url request';
            return $this->setSelfJson($content);
        }
        $content['status'] = 0;
        $content['msg'] = 'ok';
        $content['access_token'] = $token;
        return $this->setSelfJson($content);

    }

    /**
     * 获取用户列表
     */
    public function user_list()
    {
        $list = WxHelp::getUserList();
        $arr  = json_decode($list, true);
        dump($arr);
    }
}
