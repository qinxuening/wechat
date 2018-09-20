<?php
/*
 *@author: qinxuening
 *@E-mail: 2423859713@qq.com
 *@date: 2018年9月19日 下午11:13:08
 */
namespace app\admin\controller;
use app\common\controller\baseAdmin;

class Admin extends baseAdmin {
    
    public function index() {
        return json(['code' =>1, 'status' => 'success', 'info' => '后台测试'.json_encode($this->noNeedLogin)]);
    }
    
    
    
}