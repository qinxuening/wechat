<?php
/**
* @author: qxn
* @E-mial: 2423859713@qq.com
* @date: 2018年12月12日 下午4:30:50
*/
namespace app\admin\library\traits;
use we\Export;

trait Base{
   protected $list;
    /**
     * 列表
     */
    public function index($page = 1, $limit = 10) {
        if ($this->request->isAjax())
        {  
//             $this->request->filter(['strip_tags', 'htmlspecialchars']);
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $count = $this->table->where($where)->count('*');
            
            $dataCol = exportCols();
            $this->list = $this->table
                ->where($where)
                ->page($dataCol ? 1 : $page,$dataCol ? $count : $limit)
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
     * 编辑
     * @param string $ids
     */
    public function edit($ids = NULL) {
        if ($this->request->isAjax()){
            $id = input('post.id');
            $data = input('');
            $this->autoData($data);
            $this->beforeSave($data);
            unset($data['id']);
//             print_r($data);die();
            if($id) {
                $data['updatetime'] = time();
                $result = $this->table->strict(false)->where([$this->table->getPk()=> $id])->update($data);
            } else {
                $data['createtime'] = time();
                $result = $this->table->strict(false)->insert($data);
            }
            
            if(false !== $result){
                return json(['code' => 1, 'status' => 'success', 'msg' => __('Operation completed')]);
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => __('Operation failed')]);
            }
        }
        $list = $this->table->where([$this->table->getPk() => $ids])->find();
        $this->afterEdit($list);
        $this->editAssign();
        $this->assign('list', $list);
        $this->assign('return_url', request()->module().'/'.request()->controller().'/index');
        return $this->view->fetch();
    }
    

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids)
        {
            $count = $this->table->where($this->table->getPk(), 'in', explode(',', $ids))->delete();
            if ($count)
            {
                return json(['code' => 1, 'status' => 'success', 'msg' => '删除成功','url'=>'']);
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => '删除失败']);
            }
        }
        return json(['code' => -2, 'status' => 'error', 'msg' => '非法操作']);
    }
    
    /**
     * index后置操作
     */
    public function afterIndex($dataCol, $page, $limit) {
        foreach ($this->list as $k => &$v) {
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
     * 处理时间戳，转为日期
     */
    public function afterEdit(&$data) {
        foreach ($data as $k => $v) {
            if(is_timestamp($v)) {
                $data[$k] = date('Y-m-d H:i:s', $v);
            }
        }
    }
    
    public function editAssign() {
        
    }
    
    public function autoData(&$data){
        
    }
    
    /**
     * 处理时间,转为时间戳
     */
    public function beforeSave(&$data){
        foreach (array_keys($this->status) as $value) {
            if(!in_array($value, array_keys($data))) {
                $data[$value] = 0;
            }
        }

        foreach ($data as $k => &$v) {
            if($v === '' || $v === null) {
                unset($data[$k]);
            }
            if($this->getFieldType()[$k] == 'tinyint(1)') {
                $data[$k] = $data[$k] === 'on' ? '1' : 0;
            }
            if(is_date($v)) {
                $data[$k] = strtotime($v);
            }
        }
    }
    
    /**
     * 获取字段类型
     */
    public function getFieldType() {
        static $field_type = [];
        if(!$field_type) {
            $field_type = $this->table->getTableInfo()['type'];
        }
        return $field_type;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}