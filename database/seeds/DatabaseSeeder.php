<?php

use App\Models\EventType;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UsersTableSeeder::class);
        $this->menu();
        $this->event_type();
        $this->user();
    }

    private function user()
    {
        DB::table('users')->insert([
            'username'   => 'admin',
            'password'   => '$2y$10$UFmOAvg0CiU8mRPTlz.cee2syk9e6DFTHg4HjszYytOF.1UAr/YR.',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            'is_admin'   => 1,
            'is_use'     => 1,
        ]);
    }

    private function menu()
    {
        DB::table('menus')->truncate();
        DB::insert("INSERT INTO `wx_menus` VALUES ('1', '0', '了解', '', '0', '1', '', '2018-02-02 12:44:53', '2018-02-02 12:44:53')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('2', '0', '互动', '', '0', '1', '', '2018-02-02 12:45:10', '2018-02-02 12:45:10')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('3', '0', '加入', '', '0', '1', '', '2018-02-02 12:45:18', '2018-02-02 12:45:18')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('4', '1', '最In的团队', 'view', '0', '1', 'https://mp.weixin.qq.com/s/sajCIaLX7iv9USMQ3uMhdA', '2018-02-02 12:45:54', '2018-02-02 13:34:09')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('5', '1', '最In的品牌', 'view', '0', '1', 'https://mp.weixin.qq.com/s/j-VcBPxxbF6MIQMrHWnL4Q', '2018-02-02 12:46:13', '2018-02-02 12:46:13')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('6', '1', '最In的历程', 'view', '0', '1', 'https://mp.weixin.qq.com/s/Wxut20_NsK73hma6RUHE2g', '2018-02-02 12:46:39', '2018-02-02 12:46:39')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('7', '2', '微博', 'view', '0', '1', 'https://weibo.com/u/6034438868', '2018-02-02 12:54:55', '2018-02-02 12:54:55')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('8', '3', '官方网站', 'view', '0', '1', 'https://www.inditexcareers.com/', '2018-02-02 13:16:36', '2018-02-02 13:16:36')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('9', '3', '一键投递', 'view', '0', '1', 'https://www.inditexcareers.cn/', '2018-02-02 13:17:35', '2018-02-02 13:17:35')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('10', '3', '新店招聘', 'click', '0', '1', 'new_zhaopin', '2018-02-02 13:21:35', '2018-02-02 13:21:35')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('11', '2', '最佳雇主', 'click', '0', '1', 'button_employer', '2018-02-02 13:34:41', '2018-02-02 13:34:41')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('12', '2', '北京TC活动', 'view', '0', '1', 'http://q.pincn.com/inditexcareers/openday', '2018-02-02 13:34:59', '2018-02-02 13:34:59')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('13', '3', '管理培训生', 'view', '0', '1', 'https://mp.weixin.qq.com/s/TCg1xzlfBhN8YsCHjjpEpw', '2018-02-02 13:35:18', '2018-02-02 13:35:18')");
        DB::insert("INSERT INTO `wx_menus` VALUES ('14', '3', '兼职生', 'view', '0', '1', 'https://mp.weixin.qq.com/s/5saTZqcVGSY9maMhepxlcA', '2018-02-02 13:35:32', '2018-02-02 13:35:32')");
    }

    private function event_type()
    {
        EventType::truncate();
        EventType::insert([
            [
                'id'   => 1,
                'code' => 'text',
                'name' => '文字消息',
            ],
            [
                'id'   => 2,
                'code' => 'news',
                'name' => '图文消息',
            ]
        ]);
    }
}
