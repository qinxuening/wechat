<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年10月21日 下午3:30:08
*/

namespace app\admin\controller;

use app\common\controller\baseAdmin;
use app\admin\model\Admin;
use we\Random;
use think\Session;
use think\Validate;

class Profile extends baseAdmin{
    
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $rule_info = get_passwd_rule();
        if ($this->request->isAjax())
        {
            $model = model('AdminLog');
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
    
            $total = $model
            ->where($where)
            ->where('admin_id', $this->auth->id)
            ->order($sort, $order)
            ->count();
    
            $list = $model
            ->where($where)
            ->where('admin_id', $this->auth->id)
            ->order($sort, $order)
            ->limit($offset, $limit)
            ->select();
    
            $result = array("total" => $total, "rows" => $list);
    
            return json($result);
        }
        $this->view->assign('passwd_complexity_rule', $rule_info);
        return $this->view->fetch();
    }
    
    /**
     * 更新个人信息
     */
    public function update()
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("");   
            //             print_r($params);
            if (isset($params['password']) && !empty($params['password']))
            {
                $rule_info = get_passwd_rule();
                $pass_regex = 'regex:'.$rule_info[0];
    
                $rule = [
                    'password'  => $pass_regex,
                ];
    
                $message = [
                    'password.regex' => $rule_info[1]
                ];
    
                $data = ['password'  => $params['password']];
    
                $validate = new Validate($rule, $message, ['password' => __('Password')]);
    
                $result = $validate->check($data);
    
                if (!$result)
                {
                    $this->error($validate->getError());
                }
    
                if ($params['password'] != $params['confirm_password']){
                    $this->error(__('Password not equalt confirm_password'));
                }
                $params['salt'] = Random::alnum();
                $params['password'] = md5(md5($params['password']) . $params['salt']);
            }
            if ($params)
            {
                $params['status'] = $params['status'] == 'on' ? '1' : 0;
                $admin = Admin::get($this->auth->id);                                    
                if(!$params['password'])
                    unset($params['password']);
                //                 if ($params['salt'] && $params['password']){
                $admin->allowField(true)->save($params); //->allowField(true)过滤非数据表字段的数据
                //                 }
                Session::set("admin", $admin->toArray());
                return json(['code' => 1, 'status' => 'success', 'msg' => __('Operation completed')]);
            }
            return json(['code' => -1, 'status' => 'error', 'msg' => __('Illegal operation')]);
        }
        return;
    }
}