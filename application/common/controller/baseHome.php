<?php
/**
* @author: qxn
* @E-mial: 2423859713@qq.com
* @date: 2018年9月20日 上午11:57:03
*/

namespace app\common\controller;

use think\Controller;

class baseHome extends Controller{
    public function _initialize()
    {
        $this->noNeedLogin = ['index'];
        
    }
    
    public function _empty(){
        return json(['code'=>-1,'status'=>'error','info'=>'非法访问']);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}