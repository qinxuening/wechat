<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年9月23日 下午12:26:05
*/
namespace app\admin\model;

use think\Model;

class AuthGroup extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function getNameAttr($value, $data)
    {
        return __($value);
    }

}