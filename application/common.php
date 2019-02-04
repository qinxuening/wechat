<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
error_reporting(0);
// 应用公共文件
// 公共助手函数
if (!function_exists('__'))
{

    /**
     * 获取语言变量值
     * @param string    $name 语言变量名
     * @param array     $vars 动态变量值
     * @param string    $lang 语言
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name) || !$name)
            return $name;
        if (!is_array($vars))
        {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        return \think\Lang::get($name, $vars, $lang);
    }

}

if (!function_exists('format_bytes'))
{

    /**
     * 将字节转换为可读文本
     * @param int $size 大小
     * @param string $delimiter 分隔符
     * @return string
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++)
            $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }

}

if (!function_exists('datetime'))
{

    /**
     * 将时间戳转换为日期时间
     * @param int $time 时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s')
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }

}

if (!function_exists('human_date'))
{

    /**
     * 获取语义化时间
     * @param int $time 时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \fast\Date::human($time, $local);
    }

}

if (!function_exists('cdnurl'))
{

    /**
     * 获取上传资源的CDN的地址
     * @param string $url 资源相对地址
     * @return string
     */
    function cdnurl($url)
    {
        return preg_match("/^https?:\/\/(.*)/i", $url) ? $url : \think\Config::get('upload.cdnurl') . $url;
    }

}


if (!function_exists('is_really_writable'))
{

    /**
     * 判断文件或文件夹是否可写
     * @param	string $file 文件或目录
     * @return	bool
     */
    function is_really_writable($file)
    {
        if (DIRECTORY_SEPARATOR === '/')
        {
            return is_writable($file);
        }
        if (is_dir($file))
        {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === FALSE)
            {
                return FALSE;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return TRUE;
        }
        elseif (!is_file($file) OR ( $fp = @fopen($file, 'ab')) === FALSE)
        {
            return FALSE;
        }
        fclose($fp);
        return TRUE;
    }

}

if (!function_exists('rmdirs'))
{

    /**
     * 删除文件夹
     * @param string $dirname 目录
     * @param bool $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname))
            return false;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo)
        {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself)
        {
            @rmdir($dirname);
        }
        return true;
    }

}

if (!function_exists('copydirs'))
{

    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest 目标文件夹
     */
    function copydirs($source, $dest)
    {
        if (!is_dir($dest))
        {
            mkdir($dest, 0755);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST) as $item
        )
        {
            if ($item->isDir())
            {
                $sontDir = $dest . DS . $iterator->getSubPathName();
                if (!is_dir($sontDir))
                {
                    mkdir($sontDir);
                }
            }
            else
            {
                copy($item, $dest . DS . $iterator->getSubPathName());
            }
        }
    }

}

if (!function_exists('mb_ucfirst'))
{

    function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
    }

}

if (!function_exists('addtion'))
{

    /**
     * 附加关联字段数据
     * @param array $items 数据列表
     * @param mixed $fields 渲染的来源字段
     * @return array
     */
    function addtion($items, $fields)
    {
        if (!$items || !$fields)
            return $items;
        $fieldsArr = [];
        if (!is_array($fields))
        {
            $arr = explode(',', $fields);
            foreach ($arr as $k => $v)
            {
                $fieldsArr[$v] = ['field' => $v];
            }
        }
        else
        {
            foreach ($fields as $k => $v)
            {
                if (is_array($v))
                {
                    $v['field'] = isset($v['field']) ? $v['field'] : $k;
                }
                else
                {
                    $v = ['field' => $v];
                }
                $fieldsArr[$v['field']] = $v;
            }
        }
        foreach ($fieldsArr as $k => &$v)
        {
            $v = is_array($v) ? $v : ['field' => $v];
            $v['display'] = isset($v['display']) ? $v['display'] : str_replace(['_ids', '_id'], ['_names', '_name'], $v['field']);
            $v['primary'] = isset($v['primary']) ? $v['primary'] : '';
            $v['column'] = isset($v['column']) ? $v['column'] : 'name';
            $v['model'] = isset($v['model']) ? $v['model'] : '';
            $v['table'] = isset($v['table']) ? $v['table'] : '';
            $v['name'] = isset($v['name']) ? $v['name'] : str_replace(['_ids', '_id'], '', $v['field']);
        }
        unset($v);
        $ids = [];
        $fields = array_keys($fieldsArr);
        foreach ($items as $k => $v)
        {
            foreach ($fields as $m => $n)
            {
                if (isset($v[$n]))
                {
                    $ids[$n] = array_merge(isset($ids[$n]) && is_array($ids[$n]) ? $ids[$n] : [], explode(',', $v[$n]));
                }
            }
        }
        $result = [];
        foreach ($fieldsArr as $k => $v)
        {
            if ($v['model'])
            {
                $model = new $v['model'];
            }
            else
            {
                $model = $v['name'] ? \think\Db::name($v['name']) : \think\Db::table($v['table']);
            }
            $primary = $v['primary'] ? $v['primary'] : $model->getPk();
            $result[$v['field']] = $model->where($primary, 'in', $ids[$v['field']])->column("{$primary},{$v['column']}");
        }

        foreach ($items as $k => &$v)
        {
            foreach ($fields as $m => $n)
            {
                if (isset($v[$n]))
                {
                    $curr = array_flip(explode(',', $v[$n]));

                    $v[$fieldsArr[$n]['display']] = implode(',', array_intersect_key($result[$n], $curr));
                }
            }
        }
        return $items;
    }

}

if (!function_exists('var_export_short'))
{

    /**
     * 返回打印数组结构
     * @param string $var   数组
     * @param string $indent 缩进字符
     * @return string
     */
    function var_export_short($var, $indent = "")
    {
        switch (gettype($var))
        {
            case "string":
                return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
            case "array":
                $indexed = array_keys($var) === range(0, count($var) - 1);
                $r = [];
                foreach ($var as $key => $value)
                {
                    $r[] = "$indent    "
                    . ($indexed ? "" : var_export_short($key) . " => ")
                    . var_export_short($value, "$indent    ");
                }
                return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, TRUE);
        }
    }

}

if(!function_exists('myLog')) {
    /**
     * 打印日志
     * 按时间戳生成文件名
     * @param unknown $str
     */
    function myLog($str) {
        $dir = getcwd(). '/logs/';
        if(!is_dir($dir)) {
            $flag =  mkdir($dir, 0777, true);
            dump($flag);
            chmod($dir, 0777);

        }

        if(is_array($str)) {
            $str = json_encode($str);
        }

        $file = $dir . date('Ymd') . '.log.txt';
        $fp = fopen($file, 'a+');

        if (flock($fp, LOCK_EX)) {
            $content = "[" . date('Y-m-d H:i:s') . "]\r\n";
            $content .= $str . "\r\n\r\n";
            fwrite($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
            chmod($file, 0777);
        } else {
            fclose($fp);
        }
    }
}

if(!function_exists('is_json')) {
    /**
     * 判断数据是合法的json数据
     * @param unknown $string
     */
    function is_json($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}


if(!function_exists('systemLog')) {
    /**
     * 系统日志
     * 按时间戳生成文件名
     * @param unknown $str
     */
    function systemLog($str) {
        $dir = getcwd(). '/public/systemlogs/';

        if(!is_dir($dir)) {
            $flag =  mkdir($dir, 0777, true);
            dump($flag);
            chmod($dir, 0777);

        }

        $file = $dir . date('Ymd') . '.log.txt';
        $fp = fopen($file, 'a+');

        if(is_array($str)) {
            $str = json_encode($str);
        }

        if (flock($fp, LOCK_EX)) {
            $content = "[" . date('Y-m-d H:i:s') . "]\r\n";
            $content .= $str . "\r\n\r\n";
            fwrite($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
            chmod($file, 0777);
        } else {
            fclose($fp);
        }
    }
}


if(!function_exists('cliLog')) {
    /**
     * 命令执行生成日志
     */
    function cliLog($str) {
        if(is_array($str)) {
            $str = json_encode($str);
            $str = "'".$str."' | jq .";
        }
        $file = '/tmp/'. date('Ymd') . '.cli_log.txt';
        $content = "[" . date('Y-m-d H:i:s') . "]";
        system('echo '.$content.">{$file}");
        system('echo '.$str.">{$file}");
    }
}


if(!function_exists('curl_api')) {
    /**
     * curl 请求数据
     * @param unknown $url
     * @param unknown $crypt_data
     * @return \think\response\Json
     */
    function curl_api($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD , true);//TRUE 禁用 @ 前缀在 CURLOPT_POSTFIELDS 中发送文件,安全作用，使用 CURLFile 作为上传的代替
        curl_setopt($ch, CURLOPT_VERBOSE, 0); //TRUE将在安全传输时输出 SSL 证书信息到 STDERR
        curl_setopt($ch, CURLOPT_TIMEOUT,30); //超时时间
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $return_data = curl_exec($ch);
        if(!!$error = curl_error($ch)) {
            /**
             * 超时、异常处理
             */
            return json_encode(['code' => -101, 'status' => 'error', 'msg' => $error],JSON_UNESCAPED_UNICODE);
        } else {
            return $return_data;
        }
    }
}


if(!function_exists('is_timestamp')) {
    /**
     * 判断是否为正确时间戳
     * @param unknown $timestamp
     * @return unknown|boolean
     */
    function is_timestamp($timestamp) {
        if(strtotime(date('Y-m-d H:i:s', $timestamp)) == $timestamp && strlen(trim($timestamp)) == 10) {
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('is_date')) {
    function is_date($date) {
        if(date('Y-m-d H:i:s', strtotime($date)) == $date) {
            return true;
        } else {
            return false;
        }
    }
}


/**
 * 签名校验
 * @param unknown $data
 * @param number $expires
 * @return boolean
 * @author qxn
 */
function check_sign($data, $expires = 300) {
    $params = array();
    $params['timestamp'] = $data['timestamp'];

    if (time() - strtotime($params['timestamp']) > $expires) {
        myLog('签名过期或者请求超时');
        return -1; //签名已过期
    }
    if ($data['appkey'] != config('secret_key')['APPKEY']) {
        myLog('appkey错误!');
        return -2; //appkey错误
    }

    if ($data['secret'] != config('secret_key')['SECRET']){
        myLog('通信密钥错误!');
        return -3; //secret通信密钥错误
    }

    $params['appkey'] = config('secret_key')['APPKEY'];
    $params['secret'] = config('secret_key')['SECRET'];

    $sign = data_auth_sign($params);

    if ($sign !== $data['sign']) {
        myLog('签名错误!');
        return -4; //签名错误
    }

    return true;
}


/**
 * sha1加密校验
 * @param unknown $data
 * @return string
 * @author qxn
 */
function data_auth_sign($data) {
    if (!is_array($data)) {
        $data = (array) $data;
    }
    ksort($data);
    $param = array();
    foreach ($data as $key => $val) {
        $param[] = $key . "=" . $val;
    }
    $param = join("&", $param);
    $sign = sha1($param);
    return $sign;
}


/**
 * AES加密
 * @param unknown $data
 * @return string
 */
function aes_encrypt_ ($data) {
    $data = json_encode($data);
    $data = urlencode($data);
    $result =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, config('secret_key')['AES_KEY'], $data, MCRYPT_MODE_CBC,config('secret_key')['AES_IV']);
    $result = base64_encode($result);
    return $result;
}


/**
 * AES解密
 * @param unknown $data
 * @return string
 */
function aes_decrypt_($data) {
    $data= base64_decode($data);
    $result =  rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, config('secret_key')['AES_KEY'], $data, MCRYPT_MODE_CBC,config('secret_key')['AES_IV']),"\0");
    //解密后右端会有空白字符 需要手动清除 猜测是mcrypt的一个BUG
    $result= urldecode($result);
    $result= json_decode($result, true);
    return $result;
}


//返回当前的毫秒时间戳
function msectime() {
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}






