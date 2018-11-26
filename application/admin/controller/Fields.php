<?php
/*
 *@author: qinxuening
 *@E-mail: 2423859713@qq.com
 *@date: 2018年11月25日 下午1:33:45
 */
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Db;
use we\Export;

class Fields extends baseAdmin{
    private $field_type;
    private $form_type;
    private $select_rule;
    
    public function _initialize()
    {
        parent::_initialize();
        $this->field_type = ['varchar' => '字符串', 'int' => '整型', 'date' => '日期' ,'float' => '浮点型', 'text' => '长文本'];
        $this->form_type = ['text' => '文本框', 'password' => '密码框', 'radio' => '单选框', 'checkbox' => '复选框',
            'select' => '下拉框', 'textarea' => '文本域', 'address' => '地址框', 'number' => '数字框', 'datetime' => '时间框','hidden' =>'隐藏域'
        ];
        $this->select_rule = ['required' => '必填项', 'isIdCardNo' => '身份证', 'isMobile' => '手机号', 'isFloat' => '浮点数值',
            'isHuZhao' => '护照格式', 'zhRangeLength=[2,4]' =>'中文', 'ajax="url"' => 'ajax', 'isEmail' => '邮箱'
        ];
    }
    
    public function index($page = 0, $limit = 15) {
        if ($this->request->isAjax())
        {
            $count = Db::name('fields')->count('*');
            
            $dataCol = exportCols();
            
            $list = Db::name('fields')
            ->page($dataCol ? 0 : $page,$dataCol ? null : $limit)
            ->select();
            
            /**
             * 导出数据
            */
            if($dataCol) {
                foreach ($list as $k => &$v) {
                    $v['ID'] = $k + 1;
                    $v['status'] = $this->status[$v['status']];
                    $v['is_time_push'] = $this->status[$v['is_time_push']];
                }
                $field['data'] = $dataCol;
                $title = "推送管理报表";
                $action = new Export();
                $baseurl = $action->excel($list,$field,$title);
                $filename = '/downloadfile/'.$title."_".date('Y-m-d',mktime()).".xls";
                return json(['code' => 1, 'status' => 'success', 'msg' => '导出成功', 'url' => $filename]);
            }
            
            return json(['code' => 0, 'count' => $count, 'status' => 'success', 'data' => $list,'msg' => '获取成功']);
        }else{
            return $this->view->fetch();
        }
    }
    
    
    /**
     * 添加字段
     */
    public function add() {
        $this->assign('field_type', $this->field_type);
        $this->assign('form_type', $this->form_type);
        $this->assign('select_rule', $this->select_rule);
        return $this->view->fetch();
    }
    
    /**
     * 删除
     */
    public function del() {
        return $this->view->fetch();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}