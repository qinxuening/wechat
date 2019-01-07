<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年12月9日 下午5:59:33
 */

namespace app\admin\controller;
use app\common\controller\baseAdmin;

class Database extends baseAdmin{
    
    public function _initialize()
    {
        parent::_initialize();
    }
    
    /**
     * 备份列表
     */
    public function index() {
        if ($this->request->isAjax())
        {
            $path = config('DATA_BACKUP_PATH');
            if(!is_dir($path)){
                mkdir($path, 0755, true);
            }
            $path = realpath($path);
            $flag = \FilesystemIterator::KEY_AS_FILENAME;
            $glob = new \FilesystemIterator($path, $flag);
            $list = array();
            $key = -1;
            foreach ($glob as $name => $file) {
                if(preg_match('/^[A-Za-z0-9]+_\d{14}+\.sql$/', $name)){
                    $key++;
                    $list[$key]['id'] = $name;
                    $list[$key]['ID'] = $key + 1;
                    $list[$key]['backup_name'] = $name;
                    $list[$key]['size'] = format_bytes($file->getSize());
                    $list[$key]['time'] = date('Y-m-d H:i:s', strtotime(substr(explode("_", $name)[1],0,strpos(explode("_", $name)[1],'.'))));
                }
            }
            return json(['code' => 0, 'count' => count($list), 'status' => 'success', 'data' => $list,'msg' => '获取成功']);
        }else{
             return $this->view->fetch();
        }
    }
    
    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            $ids = explode(',', $ids);
            foreach ($ids as $k => $v){
                $path  = realpath(config('DATA_BACKUP_PATH')).'/'.$v;
                array_map("unlink", glob($path));
                if(count(glob($path))){
                    return json(['code' => -1, 'status' => 'error', 'msg' => __('Failed to delete')]);
                }
            }
            return json(['code' => 1, 'status' => 'success', 'msg' => __('Successfully deleted'),'url'=>'']);
        }
        return json(['code' => -2, 'status' => 'error', 'msg' => __('Illegal operation')]);
    }
    
    
    /**
     * 执行备份命令
     */
    public function dumpdata() {
        $lock = realpath(config('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR."/backup.lock";
        if(is_file($lock)){
            return json(['code' => -3, 'status' => 'error', 'msg' => '正在备份中']);
        }
        
        $backup_name = 'scan_'.date('YmdHis', time());
        $result = system("mysqldump loan > ".config('DATA_BACKUP_PATH')."{$backup_name}.sql 2>&1 &", $return_status);
        if($return_status === 0) {
            file_put_contents($lock, $_SERVER['REQUEST_TIME']);
            if(!is_writeable($lock)){
                return json(['code' => -2, 'status' => 'error', 'msg' => $lock.'文件不可写']);
            }   
            $shell = "sudo ../crons/check_backup_lock.sh > /dev/null 2>&1 &";
            exec($shell,$out);//监控执行备份
            return json(['code' => 1, 'status' => 'success',
                    'info' => [
                    'return_status' =>$return_status, 
                    'result' => $result,
                    'shell_result' => $out,
                ],
                'msg' => '正在备份'
              ]);
        } else {
            return json(['code' => -1, 'status' => 'error', 'msg' => '备份失败']);
        }

    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}