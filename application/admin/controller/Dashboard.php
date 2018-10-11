<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年10月11日 下午10:28:53
*/
namespace app\admin\controller;
use app\common\controller\baseAdmin;

class Dashboard extends baseAdmin {
    
    public function detail() {
        return $this->view->fetch();
    }
}
































