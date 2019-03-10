<?php

namespace app\admin\controller;
use app\common\controller\baseAdmin;

class MemberRank extends baseAdmin{
    protected $table;
    protected $excel_title;
    protected $table_name;
    protected $status;
    
    public function _initialize()
    {
        parent::_initialize();

    }
    
    
    
}