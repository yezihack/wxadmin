<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class QrStat extends BaseModels
{
    protected $table = 'qr_stats';
    //主键ID
    protected $primaryKey = 'id';


    public static function getData($id)
    {
        $expired = Carbon::now()->addDay(1);
        $list    = Cache::remember('qr_stat_key_' . $id, $expired, function () use ($id) {
            $table = 'wx_qr_stats';
//            $data      = DB::select("select DATE_FORMAT(created_at,'%Y-%m-%d') as date,  count(*) as cnt from {$table} where qr_id = ? group by date", [$id]);
            $data      = QrStat::selectRaw("DATE_FORMAT(created_at,'%Y-%m-%d') as Day, count(*) as cnt")
                ->groupBy('Day')->where('qr_id', $id)->get();
            $date_list = $data_list = [];
            foreach ($data as $item) {
                $date_list[] = $item->Day;
                $data_list[] = $item->cnt;
            }
            return ['date' => $date_list, 'data' => $data_list];
        });
        return $list;
    }

    /**
     * 记录统计
     * @param $fromUsername
     * @param $qr_id
     * @return bool|static
     */
    public static function record($fromUsername, $qr_id)
    {
        $stat = self::where('openid', $fromUsername)->count();
        if ($stat <= 0 || true) {
            return QrStat::create([
                'qr_id'  => $qr_id,
                'openid' => $fromUsername
            ]);
        }
        return false;
    }
}
