<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think;

use think\helper\Str;
use think\queue\Connector;

/**
 * Class Queue
 * @package think\queue
 *
 * @method static push($job, $data = '', $queue = null)
 * @method static later($delay, $job, $data = '', $queue = null)
 * @method static pop($queue = null)
 * @method static marshal()
 */
class Queue
{
    /** @var Connector */
    protected static $connector;

    private static function buildConnector()
    {
//         $options = Config::get('queue');
        $options = [
//             'connector'  => 'Redis',		    // Redis 驱动
//             'expire'     => 60,				// 任务的过期时间，默认为60秒; 若要禁用，则设置为 null
//             'default'    => 'default',		// 默认的队列名称
//             'host'       => '127.0.0.1',	    // redis 主机ip
//             'port'       => 6380,			// redis 端口
//             'password'   => '123456qxn',				// redis 密码
//             'select'     => 0,				// 使用哪一个 db，默认为 db0
//             'timeout'    => 0,				// redis连接的超时时间
//             'persistent' => false,			// 是否是长连接
        
            'connector' => 'Database',   // 数据库驱动
            'expire'    => 60,           // 任务的过期时间，默认为60秒; 若要禁用，则设置为 null
            'default'   => 'default',    // 默认的队列名称
            'table'     => 'jobs',       // 存储消息的表名，不带前缀
            'dsn'       => [],
        ];
        $type    = !empty($options['connector']) ? $options['connector'] : 'Sync';

        if (!isset(self::$connector)) {

            $class = false !== strpos($type, '\\') ? $type : '\\think\\queue\\connector\\' . Str::studly($type);

            self::$connector = new $class($options);
        }
        return self::$connector;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::buildConnector(), $name], $arguments);
    }
}
