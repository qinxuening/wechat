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
    public function index($page = 0, $limit = 10) {
        if ($this->request->isAjax())
        {
            $count = $this->table->count('*');
            
            $dataCol = exportCols();
            $this->list = $this->table
                ->page($dataCol ? 1 : $page,$dataCol ? $count : $limit)
                ->select();
            /**
             * 导出数据
             */
            if($dataCol) {
                $this->afterIndex();
                
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
            $this->afterDate($data);
            if($id) {
                $result = $this->table->where([$this->table->getPk()=> $id])->update($data);
            } else {
                unset($data['id']);
                $result = $this->table->insert($data);
            }
            
            if(false !== $result){
                return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => '非法操作']);
            }
        }
        $list = $this->table->where([$this->table->getPk() => $ids])->find();
        $this->afterTimestamp($list);
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
    public function afterIndex() {
        foreach ($this->list as $k => &$v) {
            $v['ID'] = $k + 1;
            foreach ($v as $k_ => $v_) {
                if(count($this->status[$k_]) > 0) {
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
    public function afterTimestamp(&$data) {
        foreach ($data as $k => $v) {
            if(is_timestamp($v)) {
                $data[$k] = date('Y-m-d H:i:s', $v);
            }
        }
    }
    
    /**
     * 处理时间,转为时间戳
     */
    public function afterDate(&$data){
        foreach ($data as $k => $v) {
            if(is_date($v)) {
                $data[$k] = strtotime($v);
            }
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}