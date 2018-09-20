<?php
/**
* @author: qxn
* @E-mial: 2423859713@qq.com
* @date: 2018年9月20日 下午2:07:37
*/
namespace app\admin\controller;

class Error{
    public function index() {
        return json(['code'=>-1,'status'=>'error','info'=>'非法访问']);
    }
}