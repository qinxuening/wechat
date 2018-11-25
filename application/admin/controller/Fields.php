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
    
    public function _initialize()
    {
        parent::_initialize();
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
        return $this->view->fetch();
    }
    
    /**
     * 删除
     */
    public function del() {
        return $this->view->fetch();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}