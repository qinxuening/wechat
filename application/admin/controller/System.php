<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年10月13日 下午2:47:47
*/
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Db;

class System extends baseAdmin{
    public function security() {
        if ($this->request->isPost()){
            $params = $this->request->post("row/a");
        
            $type = input('type');
            if($type == 1) {
                $result = Db::name('config')->where(['key' => 'passwd_complexity'])->update(['value' => $params['strategy']]);
        
                if(false !== $result) {
                    $this->success();
                } else {
                    $this->error();
                }
            } else {
                $result1 = Db::name('config')->where(['key' => 'login_captcha'])->update(['value' => $params['login_captcha']]);
        
                if($params['login_limit'] == 1) {
                    unset($params['login_captcha']);
                    $arr = json_encode($params);
                } else {
                    $arr = json_encode(['login_limit' => $params['login_limit']]);
                }
        
                $result2 = Db::name('config')->where(['key' => 'login_limit'])->update(['value' => $arr]);
        
                if(false !== $result1 && false !== $result2) {
                    $this->success();
                } else {
                    $this->error();
                }
            }
        
            return false;
        }

        $login_limit = json_decode($login_limit['login_limit'],true);
        $this->view->assign('Password_strategy_type', get_config('passwd_complexity'));
        $this->view->assign('login_captcha', get_config('login_captcha'));
        $this->view->assign('login_limit', $login_limit);
        return $this->view->fetch();
    }
}