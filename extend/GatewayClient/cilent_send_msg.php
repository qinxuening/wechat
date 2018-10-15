<?php
/**
* @author: qxn
* @date: 2018年8月20日 上午10:41:07
*/
require_once './Gateway.php';

/**
 * gatewayClient 3.0.0及以上版本加了命名空间
 * 而3.0.0以下版本不需要use GatewayClient\Gateway;
 **/
use GatewayClient\Gateway;

/**
 *====这个步骤是必须的====
 *这里填写Register服务的ip（通常是运行GatewayWorker的服务器ip，非0.0.0.0）和Register端口
 *注意Register服务端口在start_register.php中可以找到
 *这里假设GatewayClient和Register服务都在一台服务器上，ip填写127.0.0.1
 *注意：ip不能是0.0.0.0
 **/
Gateway::$registerAddress = '127.0.0.1:1238';

$data = '{"type":"init","content":"hello all", "user":"admin", "pass":"******"}';

// 以下是调用示例，接口与GatewayWorker环境的接口一致
// 注意除了不支持sendToCurrentClient和closeCurrentClient方法
// 其它方法都支持
Gateway::sendToAll($data);
// Gateway::sendToClient($client_id, $data);
// Gateway::closeClient($client_id);
// Gateway::isOnline($client_id);
// Gateway::bindUid($client_id, $uid);
// Gateway::isUidOnline($uid);
// Gateway::getClientIdByUid($client_id);
// Gateway::unbindUid($client_id, $uid);
// Gateway::sendToUid($uid, $data);
// Gateway::joinGroup($client_id, $group);
// Gateway::sendToGroup($group, $data);
// Gateway::leaveGroup($client_id, $group);
// Gateway::getClientCountByGroup($group);
// Gateway::getClientSessionsByGroup($group);
// Gateway::getAllClientCount();
// Gateway::getAllClientSessions();
// Gateway::setSession($client_id, $session);
// Gateway::updateSession($client_id, $session);
// Gateway::getSession($client_id);














