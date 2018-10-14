<?php
/**
* @author: qxn
* @date: 2018年8月20日 下午4:38:35
*/

namespace app\worker\controller;
// use \GatewayWorker\Gateway;
use \GatewayWorker\Lib\Gateway;
require_once __DIR__ . '/../../../extend/GatewayWorker/vendor/autoload.php';
// Autoloader::setRootPath(__DIR__);
class Push{
    protected $uid;
    protected $group_id;
    protected $username;
    
    public function __construct(){
        $userinfo = session('admin');
        $this->uid = $userinfo['id'];
        $this->group_id = 1001;
        $this->username = $userinfo['username'];
    }
    
    /*
     * 用户登录后初始化以及绑定client_id
     */
    public function bind()
    {
//      https://www.cnblogs.com/tinywan/p/9160757.html
//      设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
        Gateway::$registerAddress = '127.0.0.1:1238';
        $client_id = request()->param('client_id');
        // client_id与uid绑定
        Gateway::bindUid($client_id, $this->uid);
        // 加入某个群组（可调用多次加入多个群组）
        Gateway::joinGroup($client_id, $this->group_id);
        return Gateway::getAllUidList();;
    }
    
    
    
    // mvc后端发消息 利用GatewayClient发送 Events.php
    public function sendMessage()
    {
        // stream_socket_client(): unable to connect to tcp://127.0.0.1:1236
        $message = json_encode([
            'type'=> 'say',
            'msg'=> $this->username,
        ]);
        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
        Gateway::$registerAddress = '127.0.0.1:1238';
        // 向任意uid的网站页面发送数据
        Gateway::sendToUid($this->uid, $message);
        Gateway::sendToAll(json_encode(['code' => 1, 'msg' => '测试发送消息']));
        // 向任意群组的网站页面发送数据，如果开启，则会向页面发送两条一样的消息
        //Gateway::sendToGroup($group, $message);
    }
    
    
    
    
    
}