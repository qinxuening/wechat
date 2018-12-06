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
        $data['appkey'] = config('secret_key')['APPKEY'];
        $data['secret'] = config('secret_key')['SECRET'];
        $data['skey'] = 'szpt';
        $data['timestamp'] = date('YmdHis', time());//strtotime(20160721165055);//microtime();////time();
        
        $sign_data['appkey'] = $data['appkey'];
        $sign_data['secret'] = $data['secret'];
        $sign_data['timestamp'] = $data['timestamp'];
        
        $data['sign'] = data_auth_sign($sign_data);
        $url = "http://act.wechat.com/serverapi/Server/Api"; 
        $result = curl_api($url, aes_encrypt_($data));
        return json(json_decode($result)); 
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
}