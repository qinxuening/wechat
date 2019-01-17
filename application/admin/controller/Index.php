<?php
/*
 *@author: qinxuening
 *@E-mail: 2423859713@qq.com
 *@date: 2018年9月19日 下午11:13:08
 */
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Hook;
use think\Validate;
use think\Db;

class Index extends baseAdmin {
    protected $noNeedLogin = ['login','reg','forget'];
    protected $noNeedRight = ['index', 'logout'];
    protected $layout = '';
    
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index() {
        $return_url = input('return_url');
        if($return_url) {
//             echo urldecode($return_url);die();
            $this->assign('return_url', urldecode($return_url));
        }
        //左侧菜单
        $this->view->assign('title', __('Home'));
        return $this->view->fetch();
    }
    
    /**
     * 管理员登录
     */
    public function login()
    {
        $url = $this->request->get('url', 'index');
        $rule_info = get_passwd_rule();
        $login_captcha = Db::name('config')->where(['key' => 'login_captcha'])->column('value')[0];
    
        if ($this->auth->isLogin())
        {
            $this->success(__("You've logged in, do not login again"), $url);
        }
        if ($this->request->isPost())
        {
//             print_r($this->request->post(''));die();
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $keeplogin = $this->request->post('keeplogin');
            $token = $this->request->post('__token__');
    
            $pass_regex = 'regex:'.$rule_info[0];
    
            $rule = [
                'username'  => 'require|length:3,30',
                'password'  => 'require|'.$pass_regex,
            ];
    
            $message = [
                'password.regex' => $rule_info[1]
            ];
    
            $data = [
                'username'  => $username,
                'password'  => $password,
            ];

            if($login_captcha)
            {
                $rule['captcha'] = 'require|captcha';
                $data['captcha'] = $this->request->post('captcha');
            }
//             print_r($data);die();
                
            $validate = new Validate($rule, $message, ['username' => __('Username'), 'password' => __('Password'), 'captcha' => __('Captcha')]);
    
            $result = $validate->check($data);
    
            if (!$result)
            {
                $this->error($validate->getError(), $url, ['token' => $this->request->token()]);
            }
            
//             if($keeplogin) {
                $result = $this->auth->login($username, $password, $keeplogin ? 86400 : 0);
//             }

            if ($result === true)
            {
                $param = ['data'=>'登陆操作','code'=>1,'message'=> $username.'登陆成功'];
                Hook::listen('mark_log', $param);
                return json(['code' => 1, 'status' => 'success', 'msg' => __('Login successful'),'url' => $url,'data' => [ 'id' => $this->auth->id, 'username' => $username, 'avatar' => $this->auth->avatar]]);
            }
            else
            {
                $msg = $this->auth->getError();
                $msg = $msg ? $msg : __('Username or password is incorrect');
                return json(['code' => -1, 'status' => 'error', 'msg' => $msg]);
//                 $this->error($msg, $url, ['token' => $this->request->token()]);
            }
        }
    
        // 根据客户端的cookie,判断是否可以自动登录
        if ($this->auth->autologin())
        {
            $this->redirect($url);
        }
        
        $this->view->assign('passwd_complexity_rule', $rule_info);
        $this->view->assign('login_captcha', $login_captcha);
        $this->view->assign('title', __('Login'));
        $this->assignconfig('status',10);
        Hook::listen("login_init", $this->request);
        return $this->view->fetch();
    }
    
    /**
     * 注册
     * @return string
     */
    public function reg() {
        return $this->view->fetch();
    }
    
    /**
     * 忘记密码
     */
    public function forget() {
        return $this->view->fetch();
    }
    
    /**
     * 注销登录
     */
    public function logout()
    {
        $this->auth->logout();
        $this->redirect(url('index/login'));
    }
    
    
}