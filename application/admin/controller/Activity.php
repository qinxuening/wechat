<?php
/*
 *@author: qinxuening
 *@E-mail: 2423859713@qq.com
 *@date: 2018年11月25日 下午1:33:45
 */
namespace app\admin\controller;
use app\common\controller\baseAdmin;

class Activity extends baseAdmin{
    private $field_type;
    private $form_type;
    private $select_rule;
    protected $table;
    protected $table_name;
    
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 添加字段
     */
    public function member_sms() {
        return $this->view->fetch();
    }
    
    /**
     * 删除
     */
    public function sms_record() {
        return $this->view->fetch();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}