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
        $this->excel_title = '会员管理报表';
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
            $count = $this->table
                ->alias('u')
                ->join('__USER_GROUP__ a','a.id=u.group_id', 'LEFT')
                ->count('*');
    
            $dataCol = exportCols(); 
            $this->list = $this->table
                ->alias('u')
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
                return json(['code' => 1, 'status' => 'success', 'msg' => '导出成功', 'url' => $filename]);
            }
            return json(['code' => 0, 'count' => $count, 'status' => 'success', 'data' => $this->list,'msg' => '获取成功']);
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
    
    /**
     * 查看
     */
    /*public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with('group')
                    ->where($where)
                    ->order($sort, $order)
                    ->count();
            $list = $this->model
                    ->with('group')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            foreach ($list as $k => $v)
            {
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }*/

    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), $row['group_id'], ['class' => 'form-control selectpicker']));
        return parent::edit($ids);
    }

}
