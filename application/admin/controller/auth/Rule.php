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
//             ->page($page,$limit)
            ->select();
        Tree::instance()->init($this->rulelist);
        $this->rulelist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0), 'title');
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
       $list = Db::name('auth_rule')
               ->field('title,name,ismenu,status,weigh,createtime')
               ->order(['id'=>'desc','weigh'=>'desc'])
               ->select();
       
       foreach ($list as $k => &$v) {
           $v['ID'] = $k +1;
           $v['status'] = $this->status[$v['status']];
           $v['ismenu'] = $this->status[$v['ismenu']];
           $v['createtime'] = $v['createtime'] ? date('Y-m-d H:i:s', $v['createtime']) : 0;
       }       
       
       //第一列头
       $field['data'] = [
               ["field" => "ID", "title" => "序号", "excel_width" => 15],
               ["field" => "title", "title" => "规则名称", "excel_width" => 15],
               ["field" => "name", "title" => "规则url", "excel_width" => 30],
               ["field" => "ismenu", "title" => "是否是菜单", "excel_width" => 15],
               ["field" => "status", "title" => "状态", "excel_width" => 15],
               ["field" => "weigh", "title" => "权重", "excel_width" => 15],
               ["field" => "createtime", "title" => "创建时间", "excel_width" => 20],
       ];

       $title = "规则报表";
       $action = new Export();
       $baseurl = $action->excel($list,$field,$title);
       $filename = '/downloadfile/'.$title."_".date('Y-m-d',mktime()).".xls";

       return json(['code' => 1, 'status' => 'success', 'msg' => '导出成功', 'url' => $filename]);
   }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}