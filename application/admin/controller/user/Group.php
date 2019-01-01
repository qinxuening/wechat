<?php

namespace app\admin\controller\user;
use app\common\controller\baseAdmin;
use think\Db;

/**
 * 会员组管理
 *
 * @icon fa fa-users
 */
class Group extends baseAdmin
{

    /**
     * @var \app\admin\model\UserGroup
     */
    protected $model = null;
    protected $table;
    protected $excel_title;
    protected $table_name;
    protected $status;

    public function _initialize()
    {
        parent::_initialize();
        $this->table = Db::name('UserGroup');
        $this->table_name = 'UserGroup';
        $this->excel_title = '会员管理报表';
        $this->status = [
            'status' => ['0'=>'禁用','1'=>'启用'],
        ];
        $this->model = model('UserGroup');
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    public function add()
    {
        $nodeList = \app\admin\model\UserRule::getTreeList();
        $this->assign("nodeList", $nodeList);
        return parent::add();
    }

    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $rules = explode(',', $row['rules']);
        $nodeList = \app\admin\model\UserRule::getTreeList($rules);
        $this->assign("nodeList", $nodeList);
        return parent::edit($ids);
    }

}
