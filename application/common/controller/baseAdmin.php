<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年9月19日 下午11:13:08
*/
 
namespace app\common\controller;
use app\admin\library\Auth;
use think\Controller;

class baseAdmin extends Controller{
    protected $noNeedLogin = [];
    protected $auth = null;
    /**
     * (non-PHPdoc)
     * @see \think\Controller::_initialize()
     */
    public function _initialize()
    {
        $this->auth = Auth::instance();
        $this->noNeedLogin = ['index'];
        
    }
    
    
    public function _empty(){
        return json(['code'=>-1,'status'=>'error','info'=>'非法访问']);
    }
    
    /**
     * 渲染配置信息
     * @param mixed $name 键名或数组
     * @param mixed $value 值
     */
    protected function assignconfig($name, $value = '')
    {
        $this->view->config = array_merge($this->view->config ? $this->view->config : [], is_array($name) ? $name : [$name => $value]);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}