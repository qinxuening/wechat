<?php
/**
* @author: qxn
* @date: 2018年8月21日 下午5:12:47
*/
namespace app\worker\controller;
use \GatewayWorker\Lib\Gateway;
require_once __DIR__ . '/../../../extend/GatewayWorker/vendor/autoload.php';

class Chat{
    public static function onConnect($client_id)
    {
        $resData = [
            'type' => 'init',
            'client_id' => $client_id,
            'msg' => 'connect is success00'.date('Y-m-d H:i:s', time()) // 初始化房间信息
        ];
        Gateway::sendToClient($client_id, json_encode($resData));
    }
    
    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($client_id, $message)
    {
        // 服务端console输出
        //echo "msg : $message \r\n";
        
        // 解析数据
        $resData = json_decode($message, true);
        $type = $resData['type'];
        $roomId = $resData['roomId'];
        $userId = $resData['userId']; // 未登录，则传递一个随机
        $userName = $resData['userName']; // 未登录，则传递一个随机
        $content = isset($resData['content']) ? $resData['content'] : 'default content';

        //将时间全部置为服务器时间
        $serverTime = date('Y-m-d H:i:s', time());
        
        switch ($type) {
            case 'join':  // 用户进入直播间
                //将客户端加入到某一直播间
                Gateway::joinGroup($client_id, $roomId);
                $resData = [
                    'type' => 'join',
                    'roomId' => $roomId,
                    'userName' => $userName,
                    'msg' => "enters the Room", // 发送给客户端的消息，而不是聊天发送的内容
                    'joinTime' => $serverTime // 加入时间
                ];
                
                // 广播给直播间内所有人，谁？什么时候？加入了那个房间？
                Gateway::sendToGroup($roomId, json_encode($resData));
                break;
            case 'say':  // 用户发表评论
                $resData = [
                'type' => 'say',
                'roomId' => $roomId,
                'userName' => $userName,
                'content' => $content,
                'commentTime' => $serverTime // 发表评论时间
                ];
                // 广播给直播间内所有人
                Gateway::sendToGroup($roomId, json_encode($resData));
                break;
            case 'pong':
                break; // 接收心跳
            default:
                //Gateway::sendToAll($client_id,$json_encode($resData));
                break;
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}


