<?php

namespace app\admin\controller\user;
use app\common\controller\baseAdmin;
use we\Tree;
use think\Db;

/**
 * 会员规则管理
 *
 * @icon fa fa-circle-o
 */
class Rule extends baseAdmin
{

    /**
     * @var \app\admin\model\UserRule
     */
    protected $model = null;
    protected $rulelist = [];
    protected $multiFields = 'ismenu,status';
    protected $table;
    protected $excel_title;
    protected $table_name;
    protected $status;

    public function _initialize()
    {
        parent::_initialize();
        $this->table = Db::name('UserRule');
        $this->table_name = 'UserRule';
        $this->excel_title = '会员规则管理报表';
        $this->status = [
            'status' => ['0'=>'禁用', '1'=>'启用'],
            'ismenu' => ['0'=>'否', '1' => '否']
        ];
        $this->model = model('UserRule');
        $this->view->assign("statusList", $this->model->getStatusList());
        // 必须将结果集转换为数组
        $ruleList = collection($this->model->order('weigh', 'desc')->select())->toArray();
        foreach ($ruleList as $k => &$v)
        {
            $v['title'] = __($v['title']);
            $v['remark'] = __($v['remark']);
        }
        unset($v);
        Tree::instance()->init($ruleList);
        $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        $ruledata = [0 => __('None')];
        foreach ($this->rulelist as $k => &$v)
        {
            if (!$v['ismenu'])
                continue;
            $ruledata[$v['id']] = $v['title'];
        }
        $this->view->assign('ruledata', $ruledata);
    }
    
    /**
     * 
     * @param number $page
     * @param number $limit
     * @return \think\response\Json
     */
    public function getRule($page = 0, $limit = 10) {
        $this->rulelist = Db::name('user_rule')
        ->order(['id'=>'desc','weigh'=>'desc'])
        ->select();
        Tree::instance()->init($this->rulelist);
        $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
        $count = Db::name('auth_rule')->count('*');
        return json(['code' => 0, 'count' => $count, 'data' => $this->rulelist,'msg' => '获取成功']);
    }



}
