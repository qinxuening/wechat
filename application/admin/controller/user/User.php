<?php

namespace app\admin\controller\user;
use app\common\controller\baseAdmin;
use think\Db;
use we\Export;
/**
 * 会员管理
 *
 */
class User extends baseAdmin
{

    protected $relationSearch = true;
    protected $table;
    protected $excel_title;
    protected $table_name;
    protected $status;

    /**
     * @var \app\admin\model\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->table = Db::name('user');
        $this->table_name = 'user';
        $this->excel_title = __('Member report');
        $this->status = [];
        $this->model = model('User');
    }

    /**
     * 列表
     */
    public function index($page = 1, $limit = 10) {
        if ($this->request->isAjax())
        {
            $this->request->filter(['strip_tags']);
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $count = $this->table
                ->alias('u')
                ->where($where)
                ->join('__USER_GROUP__ a','a.id=u.group_id', 'LEFT')
                ->count('*');
    
            $dataCol = exportCols(); 
            $this->list = $this->table
                ->alias('u')
                ->where($where)
                ->join('__USER_GROUP__ a','a.id=u.group_id', 'LEFT')
                ->page($dataCol ? 1 : $page,$dataCol ? $count : $limit)
//                 ->fetchSql(true)
                ->select();

            $this->afterIndex($dataCol, $page, $limit);
            /**
             * 导出数据
            */
            if($dataCol) {
                $field['data'] = $dataCol;
                $title = $this->excel_title;
                $action = new Export();
                $baseurl = $action->excel($this->list,$field,$title);
                $filename = '/downloadfile/'.$title."_".date('Y-m-d',mktime()).".xls";
                return json(['code' => 1, 'status' => 'success', 'msg' => __('Export successful'), 'url' => $filename]);
            }
            return json(['code' => 0, 'count' => $count, 'status' => 'success', 'data' => $this->list,'msg' => __('Get completed')]);
        }else{
            return $this->view->fetch();
        }
    }
    
    /**
     * index后置操作
     */
    public function afterIndex($dataCol, $page, $limit) {
        foreach ($this->list as $k => &$v) {
            if($k == 'password') {
                unset($v);
            }
            $v['id'] = $k + 1 + ($page - 1) * $limit;
            foreach ($v as $k_ => $v_) {
                if(count($this->status[$k_]) > 0 && $dataCol) {
                    $v[$k_] = $this->status[$k_][$v_];
                }
                if(is_timestamp($v_)) {
                    $v[$k_] = date('Y-m-d H:i:s', $v_);
                }
            }
        }
    }

    public function editAssign() {
        $groupdata = Db::name('user_group')->where(['status' => 1])->column('name','id');
        $rule_info = get_passwd_rule();
        $this->view->assign('passwd_complexity_rule', $rule_info);
        $this->assign('groupdata',$groupdata);
    }

    public function black_member(){
        return $this->view->fetch();
    }
    
}
