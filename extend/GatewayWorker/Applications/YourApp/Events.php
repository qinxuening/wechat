<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
// use \think\session;
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
//     /**
//      * 当客户端连接时触发
//      * 如果业务不需此回调可以删除onConnect
//      * 
//      * @param int $client_id 连接id
//      */
//     public static function onConnect($client_id)
//     {
//         // 向当前client_id发送数据 
//         $resData = [
//             'type' => 'init',
//             'client_id' => $client_id,
//             'msg' => 'connect is success'.date('Y-m-d H:i:s', time())// 初始化房间信息
//         ];
//         Gateway::sendToClient($client_id, json_encode($resData));
//     }
    
//    /**
//     * 当客户端发来消息时触发
//     * @param int $client_id 连接id
//     * @param mixed $message 具体消息
//     */
//    public static function onMessage($client_id, $message)
//    {
//         // 向所有人发送 
//         Gateway::sendToAll("$client_id said $message\r\n");
//    }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       // 向所有人发送 
//        GateWay::sendToAll("$client_id logout\r\n");
       
       $resData = [
           'type' => 'close',
           'client_id' => $client_id,
           'msg' => 'connect is close'.date('Y-m-d H:i:s', time()) // 初始化房间信息
       ];
       GateWay::sendToAll(json_encode($resData));
   }

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
                $resData = [
                'type' => 'say',
                'roomId' => $roomId,
                'userName' => $userName,
                'content' => $content,
                'commentTime' => $serverTime // 发表评论时间
                ];
                // 广播给直播间内所有人
                Gateway::sendToClient($client_id, json_encode($resData));
                break;
                break; // 接收心跳
            default:
                //Gateway::sendToAll($client_id,$json_encode($resData));
                break;
        }
    }
    
    
}
