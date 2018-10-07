<?php
/**
* @author: qxn
* @E-mial: 2423859713@qq.com
* @date: 2018年9月21日 上午10:31:54
*/
namespace app\admin\controller\auth;

use app\common\controller\baseAdmin;
use think\Db;
use we\Tree;
use think\Cache;

class Rule extends baseAdmin{
    protected $rulelist;
    
    public function _initialize(){
        parent::_initialize();
        // 必须将结果集转换为数组
        $ruleList = Db::name('auth_rule')->order('weigh', 'desc')->select();
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
     * 规则管理
     * @return string
     */
    public function index() {
        return $this->view->fetch();
    }
    
    public function getRule($page = 0, $limit = 10) {
        $this->rulelist = Db::name('auth_rule')
            ->order(['id'=>'desc','weigh'=>'desc'])
            ->page($page,$limit)
            ->select();
        $count = Db::name('auth_rule')->count('*');
        return json(['code' => 0, 'count' => $count, 'data' => $this->rulelist,'msg' => '获取成功']);
    }
    
    public function view() {
         $id = intval(input('id'));
         $list = Db::name('auth_rule')->where(['id' => $id])->find();
         $this->assign('list', $list);
         return $this->view->fetch();
    }
    
    
    public function edit() {
        $id = intval(input('id'));
        if(request()->isPost()){
//             print_r(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);die();
            $id = input('post.id');
            $data = input('');
            $data['status'] = $data['status'] == 'on' ? '1' : 0;
            $data['ismenu'] = $data['ismenu'] == 'on' ? '1' : 0;
            if($id) {
                $result = Db::name('auth_rule')->where(['id' => $id])->update($data);
            } else {
                unset($data['id']);
                $result = Db::name('auth_rule')->where(['id' => $id])->insert($data);
            }
            
            if(false !== $result){  
                return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => '非法操作']);
            }
        }
        $list = Db::name('auth_rule')->where(['id' => $id])->find();
        $this->assign('list', $list);
        return $this->view->fetch();
    }
    

    /**
     * 删除
     */
    public function del($ids = "")
    {
//          return json(['code' => -2, 'status' => 'error', 'msg' => '非法操作']);
        if ($ids)
        {
            $delIds = [];
            foreach (explode(',', $ids) as $k => $v)
            {
                $delIds = array_merge($delIds, Tree::instance()->getChildrenIds($v, TRUE));
            }
            $delIds = array_unique($delIds);
//             print_r($ids);die();
            $count = Db::name('auth_rule')->where('id', 'in', $delIds)->delete();
            if ($count)
            {
                Cache::rm('__menu__');
               return json(['code' => 1, 'status' => 'success', 'msg' => '删除成功']);
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => '删除失败']);
            }
        }
         return json(['code' => -2, 'status' => 'error', 'msg' => '非法操作']);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}