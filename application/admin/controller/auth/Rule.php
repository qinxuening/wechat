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
use we\Export;

class Rule extends baseAdmin{
    protected $rulelist;
    protected $status;
    
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
        $this->status = [
            '0' => '否',
            '1' => '是',
        ];
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
        if ($ids)
        {
            $delIds = [];
            foreach (explode(',', $ids) as $k => $v)
            {
                $delIds = array_merge($delIds, Tree::instance()->getChildrenIds($v, TRUE));
            }
            $delIds = array_unique($delIds);
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
    
   /**
    * 导出数据
    */ 
   public function export() {
       $result = Db::name('auth_rule')
               ->field('title,name,ismenu,status,weigh,createtime')
               ->order(['id'=>'desc','weigh'=>'desc'])
               ->select();
       
       foreach ($result as $k => $v) {
           $result['ID'] = $k +1;
           $result['status'] = $this->status[$v['status']];
           $result['ismenu'] = $this->status[$v['ismenu']];
       }       
               
       //第一列头
       $field['data'] = [
               ["field" => "ID", "name" => "序号", "excel_width" => 15],
               ["field" => "title", "name" => "规则名称", "excel_width" => 15],
               ["field" => "name", "name" => "规则url", "excel_width" => 30],
               ["field" => "ismenu", "name" => "是否是菜单", "excel_width" => 15],
               ["field" => "status", "name" => "状态", "excel_width" => 15],
               ["field" => "weigh", "name" => "权重", "excel_width" => 15],
               ["field" => "createtime", "name" => "创建时间", "excel_width" => 20],
       ];
       
       $_POST["title"] = "规则报表";
       $_POST['field'] = json_encode($field);
       $_POST['list'] = json_encode($result);
       $action = new Export();
       $action->excel();
   }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}