<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年12月1日 下午5:51:19
 */
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Queue;
use think\Db;
use we\Export;

class Job extends baseAdmin{
    /**
     * 推送列表
     */
    public function index($page = 0, $limit = 10) {
        if ($this->request->isAjax())
        {
            $count = Db::name('jobs')->count('*');
            
            $dataCol = exportCols();
            $list = Db::name('jobs')
                ->page($dataCol ? 1 : $page,$dataCol ? $count : $limit)
                ->select();
            
            foreach ($list as $k => &$v) {
                $v['ID'] = $k + 1;
                $v['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
            }
            /**
             * 导出数据
             */
            if($dataCol) {
                $field['data'] = $dataCol;
                $title = "任务管理报表";
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
     * 编辑
     * @param string $ids
     */
    public function edit($ids = NULL) {
        if ($this->request->isAjax()){
            $id = input('post.id');
            $data = input('');
            $data['created_at'] = strtotime($data['created_at']);
            if($id) {
                $result = Db::name('jobs')->where(['id' => $id])->update($data);
            } else {
                unset($data['id']);
                $result = Db::name('jobs')->insert($data);
            }
    
            if(false !== $result){
                return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => '非法操作']);
            }
        }
        $list = Db::name('jobs')->where(['id' => $ids])->find();
        $list['created_at'] = date('Y-m-d H:i:s', $list['created_at']);
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
            $count = Db::name('jobs')->where('id', 'in', explode(',', $ids))->delete();
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
     * 入列测试
     */
    public function emailJob () {
        for ($i = 0; $i < 1000; $i++) {
            $emailJobClassName  = 'app\job\EmailJob';
            $jobQueueName = "emailJobQueue";
            $jobData['config']=10;
            $jobData['toemail']='242859713@qq.com';
            $jobData['name']='qin';
            $jobData['subject']='队列邮箱测试##################';
            $isPushed =  Queue::push($emailJobClassName , $jobData , $jobQueueName);
            if($isPushed) {
                echo date('Y-m-d H:i:s') . " a Job is Pushed to the MQ"."<br>";
            }
        }
    }

    
    
    
    
    
    
    
    
    
}


























