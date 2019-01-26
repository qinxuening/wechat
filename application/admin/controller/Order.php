<?php
/**
* @author: qxn
* @E-mial: 2423859713@qq.com
* @date: 2018年11月15日 下午5:51:19
*/
namespace app\admin\controller;
use app\common\controller\baseAdmin;

class Order extends baseAdmin{
    public function _initialize(){
        parent::_initialize();
    }
    
    public function index() {
        return $this->view->fetch();
    }
    
    /**
     * 获取省份
     */
    public function getAreaId() {
         return $this->view->fetch();
    }
    
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}