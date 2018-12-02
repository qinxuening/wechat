<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年12月2日 下午5:51:19
 */
namespace app\cronapi\controller;
use think\Controller;

/**
 * 
 * @author qxn
 * 后台执行脚本测试
 */
class GetInfo extends Controller{
    public function __construct(){
        $client_ip = request()->ip();
        if($client_ip != '127.0.0.1') {
            echo  json_encode(['code' => -1, 'msg' => '非法访问'],JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
    
    public function getResult() {
        set_time_limit (30); 
        ini_set("memory_limit","-1");
        ini_set('max_execution_time', 30);
        echo '#########################开始执行脚本#################';
    }

    
    
    
    
    
    
    
    
    
    
    
}
