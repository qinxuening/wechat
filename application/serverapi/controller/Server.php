<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年12月5日 下午5:51:19
 */

namespace app\serverapi\controller;
use think\Controller;

class Server extends Controller{
    private $flag;
    private $msg;
    private $decrypt_data;
    
    public function __construct() {
        $raw = file_get_contents("php://input");
        myLog($raw);
        $this->decrypt_data= aes_decrypt_($raw);
        myLog($this->decrypt_data);
        $this->flag = check_sign($this->decrypt_data,300);
        if ($this->flag === true) {
            $this->msg = json(['code' => '200', 'status' => 'success','data' => $this->decrypt_data, 'msg' => '请求成功']);
        } else {
            switch ($this->flag) {
                case -1:
                    $this->msg = json(['code' => '-101', 'status' => 'error', 'msg' => '签名过期!']);
                    break;
                case -2:
                    $this->msg = json(['code' => '-102', 'status' => 'error', 'msg' => 'appkey错误!']);
                    break;
                case -3:
                    $this->msg = json(['code' => '-103', 'status' => 'error', 'msg' => '通信密钥错误!']);
                    break;
                case -4:
                    $this->msg = json(['code' => '-104', 'status' => 'error', 'msg' => '签名错误!']);
                    break;
                default:
                    $this->msg = json(['code' => '-105', 'status' => 'error', 'msg' => '解密失败!']);
            }
        }

    }
    
    /**
     * 服务端模拟返回数据
     */
    public function Api() {
        return $this->msg;
    }

}