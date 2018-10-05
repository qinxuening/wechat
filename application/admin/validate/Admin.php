<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年10月5日 下午4:34:56
*/
namespace app\admin\validate;

use think\Validate;

class Admin extends Validate
{

    /**
     * 验证规则
     */
    protected $rule = [
        'username' => 'require|max:50|unique:admin',
        'nickname' => 'require',
        'password' => 'require',
        'email'    => 'require|email|unique:admin,email',
        'confirm_password'=>'require|confirm:password' //match(row[password]);
    ];


    /**
     * 提示消息
    */
    protected $message = [
        'confirm_password' => '{%Password not equalt confirm_password}',
    ];

    /**
     * 字段描述
    */
    protected $field = [
    ];

    /**
     * 验证场景
    */
    protected $scene = [
        'add'  => ['username', 'email', 'nickname','password','confirm_password'],
        'edit' => ['username', 'email', 'nickname'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'username' => __('Username'),
            'nickname' => __('Nickname'),
            'password' => __('Password'),
            'email'    => __('Email'),
            'confirm_password' => __('Confirm password')
        ];
        parent::__construct($rules, $message, $this->field);
    }

}