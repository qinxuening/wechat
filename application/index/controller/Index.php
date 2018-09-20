<?php
namespace app\index\controller;

use app\common\controller\baseHome;

class Index extends baseHome
{
    public function index()
    {
//         return config('site_info');
        return json(['code' =>1, 'status' => 'success', 'info' => '前端测试']);
    }
}
