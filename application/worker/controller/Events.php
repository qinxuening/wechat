<?php
/**
 * @author: qxn
 * @date: 2018年8月23日 下午2:46:42
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
// declare(ticks=1);
/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose
 */
namespace app\worker\controller;

use \GatewayWorker\Lib\Gateway;

class Events
{

    /**
     * 有消息时
     *
     * @param int $client_id            
     * @param mixed $message            
     */
    // public static function onMessage($client_id, $message)
    // {
    // // debug
    // echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']} client_id:$client_id session:" . json_encode($_SESSION) . " onMessage:" . $message . "\n";
    
    // // 客户端传递的是json数据
    // $message_data = json_decode($message, true);
    // if (! $message_data) {
    // return;
    // }
    
    // // 根据类型执行不同的业务
    // switch ($message_data['type']) {
    // // 客户端回应服务端的心跳
    // case 'pong':
    // return;
    // // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室
    // case 'login':
    // // 判断是否有房间号
    // if (!isset($message_data['room_id'])) {
    // throw new \Exception("{$message_data['room_id']} not set. client_ip:{$_SERVER['REMOTE_ADDR']} message:{$message}");
    // }
    
    // // 把房间号昵称放到session中
    // $room_id = $message_data['room_id'];
    // $client_name = htmlspecialchars($message_data['client_name']);
    // session('room_id') = $room_id;
    // session('client_name') = $client_name;
    
    // // 获取房间内所有用户列表
    // $clients_list = Gateway::getClientSessionsByGroup($room_id);
    // foreach ($clients_list as $tmp_client_id => $item) {
    // $clients_list[$tmp_client_id] = $item['client_name'];
    // }
    // $clients_list[$client_id] = $client_name;
    
    // // 转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx}
    // $new_message = array(
    // 'type' => $message_data['type'],
    // 'client_id' => $client_id,
    // 'client_name' => htmlspecialchars($client_name),
    // 'time' => date('Y-m-d H:i:s')
    // );
    // Gateway::sendToGroup($room_id, json_encode($new_message));
    // Gateway::joinGroup($client_id, $room_id);
    
    // // 给当前用户发送用户列表
    // $new_message['client_list'] = $clients_list;
    // Gateway::sendToCurrentClient(json_encode($new_message));
    // return;
    
    // // 客户端发言 message: {type:say, to_client_id:xx, content:xx}
    // case 'say':
    // // 非法请求
    // if (! isset(session('room_id'))) {
    // throw new \Exception(session('room_id')." not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
    // }
    // $room_id = session('room_id');
    // $client_name = session('client_name');
    
    // // 私聊
    // if ($message_data['to_client_id'] != 'all') {
    // $new_message = array(
    // 'type' => 'say',
    // 'from_client_id' => $client_id,
    // 'from_client_name' => $client_name,
    // 'to_client_id' => $message_data['to_client_id'],
    // 'content' => "<b>对你说: </b>" . nl2br(htmlspecialchars($message_data['content'])),
    // 'time' => date('Y-m-d H:i:s')
    // );
    // Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
    // $new_message['content'] = "<b>你对" . htmlspecialchars($message_data['to_client_name']) . "说: </b>" . nl2br(htmlspecialchars($message_data['content']));
    // return Gateway::sendToCurrentClient(json_encode($new_message));
    // }
    
    // $new_message = array(
    // 'type' => 'say',
    // 'from_client_id' => $client_id,
    // 'from_client_name' => $client_name,
    // 'to_client_id' => 'all',
    // 'content' => nl2br(htmlspecialchars($message_data['content'])),
    // 'time' => date('Y-m-d H:i:s')
    // );
    // return Gateway::sendToGroup($room_id, json_encode($new_message));
    // }
    // }
    
    // /**
    // * 当客户端断开连接时
    // *
    // * @param integer $client_id
    // * 客户端id
    // */
    // public static function onClose($client_id)
    // {
    // // debug
    // echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']} client_id:$client_id onClose:''\n";
    
    // // 从房间的客户端列表中删除
    // if (isset(session('room_id'))) {
    // $room_id = session('room_id');
    // $new_message = array(
    // 'type' => 'logout',
    // 'from_client_id' => $client_id,
    // 'from_client_name' => session('client_name'),
    // 'time' => date('Y-m-d H:i:s')
    // );
    // Gateway::sendToGroup($room_id, json_encode($new_message));
    // }
    // }
    
    /**
     * 当用户断开连接时触发
     * 
     * @param int $client_id
     *            连接id
     */
    public static function onClose($client_id)
    {
        // 向所有人发送
        $resData = [
            'type' => 'close',
            'client_id' => $client_id,
            'msg' => 'connect is close' . date('Y-m-d H:i:s', time()) // 初始化房间信息
        ];
        unset($_SESSION['']);
        session_destroy();
        GateWay::sendToAll(json_encode($resData));
    }

    public static function onConnect($client_id)
    {
        $resData = [
            'type' => 'init',
            'client_id' => $client_id,
            'msg' => 'connect is success----' . date('Y-m-d H:i:s', time()) // 初始化房间信息
        ];
        
        Gateway::sendToClient($client_id, json_encode($resData));
    }

    /**
     * 当客户端发来消息时触发
     * 
     * @param int $client_id
     *            连接id
     * @param mixed $message
     *            具体消息
     */
    public static function onMessage($client_id, $message)
    {

        $message= json_decode($message, true);
        $type = $message['type'];
        $uid = $message['id'];
        $serverTime = date('Y-m-d H:i:s', time());
        
        switch ($type) {
            case 'init': // 用户进入直播间 \将客户端加入到某一直播间
                $group_id = $message['group_id'];
                $_SESSION = [
                    'id' => $uid,
                    'group_id' => $message['group_id'],
                    'username' => $message['username'],
                    'nickname' => $message['nickname'],
                    'initTime' => $serverTime
                ];

                $msg = ['username' => $message['username'], 'initTime' => $serverTime];
                GateWay::sendToAll(json_encode($msg));  //发送给所有人
                Gateway::bindUid($client_id, $uid); // 将当前链接与uid绑定
                
                foreach ($group_id as $k => $v) {
                    Gateway::joinGroup($client_id, $v); // 加入特定组
//                     Gateway::sendToGroup($v, json_encode($msg));// 广播给直播间内所有人，谁？什么时候？加入了那个房间？
                }

                break;
            default:
                // Gateway::sendToAll($client_id,$json_encode($resData));
                break;
        }
    }
}