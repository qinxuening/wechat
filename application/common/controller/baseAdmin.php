<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年9月19日 下午11:13:08
*/
 
namespace app\common\controller;
use think\Controller;

class baseAdmin extends Controller{
    protected $noNeedLogin = [];
    
    /**
     * (non-PHPdoc)
     * @see \think\Controller::_initialize()
     */
    public function _initialize()
    {
        $this->noNeedLogin = ['index'];
        
    }
    
    
    public function _empty(){
        return json(['code'=>-1,'status'=>'error','info'=>'非法访问']);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}