<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年12月5日 下午5:51:19
 */
namespace app\cronapi\controller;
use think\Controller;

class CurlApi extends Controller{
    
    public function getData() {
        $data['appid'] = 10;
        $data['status'] = 1;
        $data['skey'] = 'szpt';
        
        $url = "";
        
        $result = curl_api($url, $data);
        
        echo $result;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
}