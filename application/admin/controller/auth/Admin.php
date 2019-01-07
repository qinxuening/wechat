<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年10月3日 下午5:33:39
*/
namespace app\admin\controller\auth;
use app\common\controller\baseAdmin;
use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
use we\Random;
use we\Tree;
use think\Db;
use we\Export;


class Admin extends baseAdmin {
    
    protected $model = null;
    protected $childrenGroupIds = [];
    protected $childrenAdminIds = [];
    private $status;
    
    public function _initialize()
    {
        parent::_initialize();
        $this->status = ['0'=>'禁用','1'=>'启用'];
        $this->model = model('Admin');
    
        $this->childrenAdminIds = $this->auth->getChildrenAdminIds(true);
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds(true);
    
        $groupList = collection(AuthGroup::where('id', 'in', $this->childrenGroupIds)->select())->toArray();
    
        Tree::instance()->init($groupList);
        $groupdata = [];
        if ($this->auth->isSuperAdmin())
        {
            $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
            foreach ($result as $k => $v)
            {
                $groupdata[$v['id']] = $v['name'];
            }
        }
        else
        {
            $result = [];
            $groups = $this->auth->getGroups();
            foreach ($groups as $m => $n)
            {
                $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['id']));
                $temp = [];
                foreach ($childlist as $k => $v)
                {
                    $temp[$v['id']] = $v['name'];
                }
                $result[__($n['name'])] = $temp;
            }
            $groupdata = $result;
        }
//         print_r($groupdata);die();
        $this->assign('statusList', json_encode(['0'=>'禁用','1'=>'启用'],JSON_FORCE_OBJECT));
        $this->view->assign('groupdata', $groupdata);
        $this->assignconfig("admin", ['id' => $this->auth->id]);
    }
    
    /**
     * 查看
     */
    public function index($page = 0, $limit = 10)
    {
        if ($this->request->isAjax())
        {
            $childrenGroupIds = $this->childrenGroupIds;
            $groupName = AuthGroup::where('id', 'in', $childrenGroupIds)
                 ->column('id,name');

            $authGroupList = AuthGroupAccess::where('group_id', 'in', $childrenGroupIds)
                ->field('uid,group_id')
                ->select();

            $adminGroupName = [];
            foreach ($authGroupList as $k => $v)
            {
                if (isset($groupName[$v['group_id']]))
                    $adminGroupName[$v['uid']][$v['group_id']] = $groupName[$v['group_id']];
            }

            $groups = $this->auth->getGroups();

            foreach ($groups as $m => $n)
            {
                $adminGroupName[$this->auth->id][$n['id']] = __($n['name']);
            }
            
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $dataCol = exportCols();
            
            $list = Db::name('admin')
                ->where('id', 'in', $this->childrenAdminIds)
                ->where($where)
                ->field(['password', 'salt', 'token'], true)
                ->page($dataCol ? 0 : $page,$dataCol ? null : $limit)
                ->select();

            $count = Db::name('admin')->where('id', 'in', $this->childrenAdminIds)->count('*');
            foreach ($list as $k => &$v)
            {
                $groups = isset($adminGroupName[$v['id']]) ? $adminGroupName[$v['id']] : [];
                $v['groups'] = implode(',', array_keys($groups));
                $v['groups_text'] = implode(',', array_values($groups));
                $v['logintime'] = $v['logintime'] ? date('Y-m-d H:i:s', $v['logintime']) : '';
            }
            unset($v);
            
            /**
             * 导出数据
             */
            if($dataCol) {
                foreach ($list as $k => &$v) {
                    $v['ID'] = $k + 1;
                    $v['status'] = $this->status[$v['status']];
                }
                $field['data'] = $dataCol;
                $title = "账号管理报表";
                $action = new Export();
                $baseurl = $action->excel($list,$field,$title);
                $filename = '/downloadfile/'.$title."_".date('Y-m-d',mktime()).".xls";
                return json(['code' => 1, 'status' => 'success', 'msg' => __('Operation completed'), 'url' => $filename]);
            }
            
            return json(['code' => 0, 'status' => 'success', 'count' => $count, 'data' => $list,'msg' => __('Get completed')]);
        }
        return $this->view->fetch();
    }
    
    /**
     * 添加
     */
    public function add()
    {
        $rule_info = get_passwd_rule();
        if ($this->request->isPost())
        {
            $params = $this->request->post("");
            if ($params)
            {
                $adminValidate = \think\Loader::validate('Admin');
    
                $adminValidate->rule([
                    'username' => 'require|max:50|unique:admin,username',
                    //'email'    => 'require|email|unique:admin,email',
                    'email'    => 'require|email|unique:admin,email',
    
                ]);
    
                if ($params['password'] && !preg_match('/'.$rule_info[0].'/', $params['password'])){
                    $this->error($rule_info[1]);
                }
    
                $params['salt'] = Random::alnum();
                $params['password'] = md5(md5($params['password']) . $params['salt']);
                $params['confirm_password'] = md5(md5($params['confirm_password']) . $params['salt']);
                //                 $params['avatar'] = '/assets/img/avatar.png'; //设置新管理员默认头像。
                //                 print_r($params);die();
                $params['status'] = $params['status'] == 'on' ? '1' : 0;
                $result = $this->model->allowField(true)->validate('Admin.add')->save($params);
                //                 print_r($result);die();
                if ($result === false)
                {
                    $this->error($this->model->getError());
                }
                $group = $this->request->post("group");
    
                //过滤不允许的组别,避免越权
                $group = array_intersect($this->childrenGroupIds, explode(',', $group));
                $dataset = [];
                foreach ($group as $value)
                {
                    $dataset[] = ['uid' => $this->model->id, 'group_id' => $value];
                }
                model('AuthGroupAccess')->saveAll($dataset);
                return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);
            }
            return json(['code' => -1, 'status' => 'error', 'msg' => '非法操作']);
        }
        $this->view->assign('passwd_complexity_rule', $rule_info);
        return $this->view->fetch();
    }
    
    /**
     * 编辑
     */
    public function edit($ids = NULL)
    {
        $row = $this->model->get(['id' => $ids]);
        $rule_info = get_passwd_rule();
//         if (!$row)
//             $this->error(__('No Results were found'));
        if ($this->request->isPost())
        {
            $params = $this->request->post("");
            $row = $this->model->get(['id' => $params['id']]);
//             print_r($params);exit();
            if ($params)
            {
                $confirm_password = $params['password'];
                if ($params['password'])
                {
                    $params['salt'] = Random::alnum();
                    $params['password'] = md5(md5($params['password']) . $params['salt']);
                }
                else
                {
                    unset($params['password'], $params['salt']);
                }
                //这里需要针对username和email做唯一验证
                $adminValidate = \think\Loader::validate('Admin');
                 
                $adminValidate->rule([
                    'username' => 'require|max:50|unique:admin,username,' . $row->id,
                    //'email'    => 'require|email|unique:admin,email',
                    'email'    => 'require|email|unique:admin,email,' . $row->id,
    
                ]);
    
                if ($confirm_password && !preg_match('/'.$rule_info[0].'/', $confirm_password)){
                    $this->error($rule_info[1]);
                }
    
                if ($confirm_password != $params['confirm_password']) {
                    $this->error(__('Password not equalt confirm_password'));
                }
    
                //                 if ($confirm_password && (md5(md5($confirm_password) . $row['salt'])!= $row['password'])) {
                //                     $this->error(__('Password check error'));
                //                 }
                $params['status'] = $params['status'] == 'on' ? '1' : 0;
                $result = $row->allowField(true)->validate('Admin.edit')->save($params); //allowField过滤非数据表字段的数据
                if ($result === false)
                {
                    $this->error($row->getError());
                }
    
                // 先移除所有权限
                model('AuthGroupAccess')->where('uid', $row->id)->delete();
    
                $group = $this->request->post("group");

                //                 print_r($this->childrenGroupIds);die();
    
                // 过滤不允许的组别,避免越权
                $group = array_intersect($this->childrenGroupIds, explode(',', $group)); //函数用于比较两个（或更多个）数组的键值，并返回交集
    
                //                 print_r($group);die();
    
                $dataset = [];
                foreach ($group as $value)
                {
                    $dataset[] = ['uid' => $row->id, 'group_id' => $value];
                }
                //                 print_r($dataset);die();
                model('AuthGroupAccess')->saveAll($dataset);
                return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);
            }
            return json(['code' => -1, 'status' => 'error', 'msg' => '非法操作']);
        }

        $grouplist = $this->auth->getGroups($row['id']);

        $groupids = [];
        foreach ($grouplist as $k => $v)
        {
            $groupids[] = $v['id'];
        }
//         print_r($groupids);die();
        $this->view->assign("list", $row);
        $this->view->assign('passwd_complexity_rule', $rule_info);
        $this->view->assign("groupids", $groupids);
        return $this->view->fetch();
    }
    
    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            // 避免越权删除管理员
            $childrenGroupIds = $this->childrenGroupIds;
            $adminList = $this->model->where('id', 'in', $ids)->where('id', 'in', function($query) use($childrenGroupIds) {
                $query->name('auth_group_access')->where('group_id', 'in', $childrenGroupIds)->field('uid');
            })->select();
            if ($adminList)
            {
                $deleteIds = [];
                foreach ($adminList as $k => $v)
                {
                    $deleteIds[] = $v->id;
                }
                $deleteIds = array_diff($deleteIds, [$this->auth->id]);
                if ($deleteIds)
                {
                    $this->model->destroy($deleteIds);
                    model('AuthGroupAccess')->where('uid', 'in', $deleteIds)->delete();
                    return json(['code' => 1, 'status' => 'success', 'msg' => '删除成功']);
                }
            }
        }
        return json(['code' => -1, 'status' => 'error', 'msg' => '删除失败']);
    }
    
    /**
     * 导出数据
     */
    /*public function export() {
        $data = exportCols();
        
        list($where, $sort, $order, $offset, $limit) = $this->buildparams();
        
        $list = Db::name('admin')
            ->where('id', 'in', $this->childrenAdminIds)
            ->where($where)
            ->field(['password', 'salt', 'token'], true)
            ->select();

        
        $field['data'] = $data;
        
        $title = "账号管理报表";
        $action = new Export();
        $baseurl = $action->excel($list,$field,$title);
        $filename = '/downloadfile/'.$title."_".date('Y-m-d',mktime()).".xls";
        
        return json(['code' => 1, 'status' => 'success', 'msg' => '导出成功', 'url' => $filename]);
    }*/
    
    
    /**
     * 批量更新
     * @internal
     */
    public function multi($ids = "")
    {
        // 管理员禁止批量操作
        $this->error();
    }
    
    /**
     * 下拉搜索
     */
    protected function selectpage()
    {
        $this->dataLimit = 'auth';
        $this->dataLimitField = 'id';
        return parent::selectpage();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}