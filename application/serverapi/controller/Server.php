<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年12月5日 下午5:51:19
 */

namespace app\serverapi\controller;
use think\Controller;

class Server extends Controller{
    
    /**
     * 服务端模拟返回数据
     */
    public function Api() {
        $raw = file_get_contents("php://input");
        sleep(30);
        myLog($raw);
        return json(['code' => '200', 'status' => 'success', 'msg' => '请求成功']);
    }
}