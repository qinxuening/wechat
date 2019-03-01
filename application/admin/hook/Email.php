<?php
/**
* @author: qxn
* @date: 2018年7月2日 下午4:55:17
*/

namespace app\admin\hook;

class Email{
    
    public function run(&$param) {
         
        if(is_array($param)) {
            $str = json_encode($param,JSON_UNESCAPED_UNICODE);
        }

        $dir = getcwd(). '/logs/';
        if(!is_dir($dir)) {
            $flag =  mkdir($dir, 0777, true);
            chmod($dir, 0777);
            
        }
        $file = $dir . date('Ymd') . '.log.txt';
        $fp = fopen($file, 'a+');
        
        if (flock($fp, LOCK_EX)) {
            $content = "[" . date('Y-m-d H:i:s') . "]\r\n";
            $content .= $str. "\r\n\r\n";
            fwrite($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
            chmod($file, 0777);
        } else {
            fclose($fp);
        }
    }

}