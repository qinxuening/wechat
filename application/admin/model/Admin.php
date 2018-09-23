<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年9月23日 下午12:42:31
*/
namespace app\admin\model;
use think\Model;

class Admin extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    //     protected $rule = [
    //         'username' => 'require|max:50|unique:admin',
    //         'nickname' => 'require',
    //         'password' => 'require',
    //         'email'    => 'require|email|unique:admin,email',
    //         'confirm_password'=>'require|confirm:password' //match(row[password]);
    //     ];
    /**
     * 重置用户密码
     * @author baiyouwen
     */
    public function resetPassword($uid, $NewPassword)
    {
        $passwd = $this->encryptPassword($NewPassword);
        $ret = $this->where(['id' => $uid])->update(['password' => $passwd]);
        return $ret;
    }

    // 密码加密
    protected function encryptPassword($password, $salt = '', $encrypt = 'md5')
    {
        return $encrypt($password . $salt);
    }

}