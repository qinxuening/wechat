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
    
    public function prograss() {
        $data[] = ['prograss' => "开会", "time"=>"一小时", "complete" => "已完成", "LAY_CHECKED" => true];
        $data[] = ['prograss' => "项目开发", "time"=>"两小时", "complete" => "进行中", "LAY_CHECKED" => true];
        $data[] = ['prograss' => "陪吃饭", "time"=>"两小时", "complete" => "未完成"];
        $data[] = ['prograss' => "修改小bug", "time"=>"半小时", "complete" => "未完成"];
        $data[] = ['prograss' => "修改大bug", "time"=>"两小时", "complete" => "未完成"];
        $data[] = ['prograss' => "修改小bug", "time"=>"半小时", "complete" => "未完成"];
        $data[] = ['prograss' => "修改大bug", "time"=>"两小时", "complete" => "未完成"];
        
        return json(['code' => 0, 'data' => $data, 'count' => 100,'msg' => '']);
    }
    
    public function console() {
        return $this->view->fetch();
    }
    
    public function line() {
        return $this->view->fetch();
    }
    
    public function bar() {
       return $this->view->fetch();
    }
}
































