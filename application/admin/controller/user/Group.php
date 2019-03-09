<?php

namespace app\admin\controller\user;
use app\common\controller\baseAdmin;
use think\Db;
use app\admin\library\Category;

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
        $this->excel_title = __('Group report');
        $this->status = [
            'status' => ['0'=>__('Off'),'1'=>__('On')],
        ];
        $this->model = model('UserGroup');
        $this->view->assign("statusList", $this->model->getStatusList());
    }
    
    public function roletree() {
        $row = $this->model->get(input('ids'));
        $allnode = Db::name('user_rule')->field('id,pid,title as name')->select();
        foreach ($allnode as $k => &$v){
            $v['value'] = $v['id'];
        }
        $rules = explode(',', $row['rules']);
        $arr_ = Category::unlimiteForLayer($allnode,'list',$rules,true);
        return json(['code' =>1, 'msg' => __('Get completed'), 'data' => ['trees' => $arr_]]);
    }

    public function autoData(&$data){
        $data['rules'] = implode(',', $data['authids']);
    }
    
    public function member_rank(){
        return $this->view->fetch();
    }
    
}
