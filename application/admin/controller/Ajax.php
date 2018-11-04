<?php
/*
 *@author: qinxuening
 *@E-mail: 2423859713@qq.com
 *@date: 2018年11月3日 
 */
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Cache;

class Ajax extends baseAdmin {
    
    public function clearcache() {
        $type = $this->request->request("type");
        switch ($type) {
            case 'content' || 'all':
                rmdirs(CACHE_PATH, false);
                Cache::clear();
                if ($type == 'content')
                    break;
            case 'template' || 'all':
                rmdirs(TEMP_PATH, false);
                if ($type == 'template')
                    break;
            case 'log' || 'all':
                rmdirs(LOG_PATH, false);
                if ($type == 'log')
                    break;
        }
        return json(['code' => 1, 'status' => 'success', 'msg' => '清除成功']);
    }
}