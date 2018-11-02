<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年10月13日 下午2:47:47
*/
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Db;
use think\db\Query;

class System extends baseAdmin{
    public function security() {
        if ($this->request->isPost()){
            $params = $this->request->post("");
        
            $type = input('type');
            if($type == 1) {
                $result = Db::name('config')->where(['key' => 'passwd_complexity'])->update(['value' => $params['strategy']]);
        
                if(false !== $result) {
                   return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);
                } else {
                   return json(['code' => -1, 'status' => 'error', 'msg' => '非法操作']);
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
                     return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);
                } else {
                    return json(['code' => -1, 'status' => 'error', 'msg' => '非法操作']);
                }
            }
        
            return false;
        }
        $login_limit = get_config('login_limit');
        $login_limit = json_decode($login_limit['login_limit'],true);
        $this->view->assign('Password_strategy_type', get_config('passwd_complexity'));
        $this->view->assign('login_captcha', get_config('login_captcha'));
        $this->view->assign('login_limit', $login_limit);
        return $this->view->fetch();
    }
    
    /**
     * 系统基本信息
     * @return string
     */
    public function base_info() {
        $version = Db::name()->query('select VERSION() as version');
        $version = $version[0]['version'];
        $info = [
            'OPERATING_SYSTEM' => PHP_OS,
            'OPERATING_ENVIRONMENT' => $_SERVER["SERVER_SOFTWARE"],
            'PHP_RUN_MODE' => php_sapi_name(),
            'MYSQL_VERSION' => $version,
            'UPLOAD_MAX_FILESIZE' => ini_get('upload_max_filesize'),
            'MAX_EXECUTION_TIME' => ini_get('max_execution_time') . "s",
            'DISK_FREE_SPACE' => format_bytes(@disk_free_space(".")) ,//round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
        ];

        $this->assign('server_info', $info);
//         print_r(config('system_info'));die();
        $this->assign('system_info', config('system_info'));
        return $this->view->fetch();
    }
    
    /**
     * 常规设置
     * @return string
     */
    public function base_set() {
        if ($this->request->isPost()){
            $type = input('type');
            switch ($type) {
                case 1:
                    break;
                case 2:
                    $data = input('post.');
                    foreach ($data as $k => $v) {
                        if (!trim($v)) {
                            return json(['code' => -2, 'status' => 'error', 'msg' => '表单字段'.$k.'不能为空']);
                        }
                    }
                    
                    $config_file = "../config/extra/email.php";
                    $result = set_config($config_file,$data);
                    
                    if($result !== false) {
                        return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);
                    } else {
                        return json(['code' => -3, 'status' => 'error', 'msg' => '配置失败']);
                    }
                    break;
                default:
                    return json(['code' => -1, 'status' => 'error', 'msg' => '非法操作']);
            }
        }
        
        $this->assign('email_config', config('email'));
        $this->assign('mail_type_list',['SSL'=>'SSL', 'TLS' => 'TLS']);
        $this->assign('send_type_list',['SMTP'=>'SMTP', 'Mail' => 'Mail']);
        return $this->view->fetch();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}