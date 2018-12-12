<?php
/**
* @author: qxn
* @E-mial: 2423859713@qq.com
* @date: 2018年12月7日 下午5:59:33
*/

namespace app\cronapi\controller;
use think\Controller;

class Mysqldump extends Controller{
    public function __construct(){
        $client_ip = request()->ip();
        if($client_ip != '127.0.0.1') {
            echo  json_encode(['code' => -1, 'msg' => '非法访问'],JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
    
    public function dumpdata() {
       $backup_name = 'scan_'.date('YmdHis', time());
       system("mysqldump scan > ../database/{$backup_name}.sql &");
    }

    /**
     * 查看当前数据库备份是否完成, 已经不用
     */
    public function checkBackupLock() {
        $lock = realpath(config('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR."/backup.lock";
        $path = config('DATA_BACKUP_PATH');
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        $path = realpath($path);
        $flag = \FilesystemIterator::KEY_AS_FILENAME;
        $glob = new \FilesystemIterator($path, $flag);
        $list = [];
        $strtime = [];

        foreach ($glob as $name => $file) {
            if(preg_match('/^[A-Za-z0-9]+_\d{14}+\.sql$/', $name)){
                array_push($list, $name);
                array_push($strtime, substr(explode("_", $name)[1],0,strpos(explode("_", $name)[1],'.')));
            }
        }
        
        asort($strtime);
        if(count($list) > 0) {
            foreach ($glob as $name => $file) {
                if($strtime[count($list) - 1] == substr(explode("_", $name)[1],0,strpos(explode("_", $name)[1],'.'))) {
                    $latest_task = $name;
                }
            }
        }
        
        $result = system("head -1 ".config('DATA_BACKUP_PATH').$latest_task.' 2>&1', $return_head);
        if (strpos($result, "MySQL dump") != false){
        } else {
            myLog('备份失败');
            unlink("../database/backup.lock"); //删除锁文件
            system('echo true > ../crons/logs_backup.txt');
            exit();
        }
        
        $last_info = system("tail -1 ".config('DATA_BACKUP_PATH').$latest_task.' 2>&1', $return_head);
        if(strpos($last_info,'Dump completed') != false) {
            myLog('备份完成');
            unlink("../database/backup.lock"); //删除锁文件
            system('echo true > ../crons/logs_backup.txt');
            exit();
            #return true;
        }
        system('echo false > ../crons/logs_backup.txt');
        #return false;
        
        system("ps -ef | grep mysqldump |grep -v grep | awk '{print $2}'");
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}