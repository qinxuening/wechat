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
            $glob = new \FilesystemIterator($path,  $flag);
            $list = array();
            $key = -1;
            foreach ($glob as $name => $file) {
                $key++;
                $list[$key]['id'] = $name;
                $list[$key]['ID'] = $key + 1;
                $list[$key]['backup_name'] = $name;
                $list[$key]['size'] = format_bytes($file->getSize());
                $list[$key]['time'] = date('Y-m-d H:i:s', strtotime(substr(explode("_", $name)[1],0,strpos(explode("_", $name)[1],'.'))));
            }
            return json(['code' => 0, 'count' => count($glob), 'status' => 'success', 'data' => $list,'msg' => '获取成功']);
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
                    return json(['code' => -1, 'status' => 'error', 'msg' => '删除失败']);
                }
            }
            return json(['code' => 1, 'status' => 'success', 'msg' => '删除成功','url'=>'']);
        }
        return json(['code' => -2, 'status' => 'error', 'msg' => '非法操作']);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}