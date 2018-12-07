<?php
/**
* @author: qxn
* @E-mial: 2423859713@qq.com
* @date: 2018年12月7日 下午5:59:33
*/

namespace app\cronapi\controller;
use think\Controller;

class Mysqldump extends Controller{
    
    public function dumpdata() {
       system("nohup mysqldump scan > scan.sql &");
    }

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}