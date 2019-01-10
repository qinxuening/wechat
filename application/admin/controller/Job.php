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

class Job extends baseAdmin{
    protected $table;
    protected $excel_title;
    protected $table_name;
    protected $status;
    
    public function _initialize()
    {
        parent::_initialize();
        $this->table = Db::name('jobs');
        $this->table_name = 'jobs';
        $this->excel_title = __('Job report');
        $this->status = [];
    }

    /**
     * 入列测试，发布任务，生产者
     */
    public function emailJob () {
        for ($i = 0; $i < 1000; $i++) {
            $emailJobClassName  = 'app\job\EmailJob';
            //如果一个任务类里有多个小任务的话，如上面的例子二，需要用@+方法名app\lib\job\Job2@task1、app\lib\job\Job2@task2
            $jobQueueName = "emailJobQueue";
            $jobData['config']=10;
            $jobData['toemail']='242859713@qq.com';
            $jobData['name']='qin';
            $jobData['subject']='队列邮箱测试##################';
            $isPushed =  Queue::push($emailJobClassName , $jobData , $jobQueueName);
            //Queue::later($delay, $job, $data = '', $queue = null) //$delay秒后执行
            if($isPushed) {
                echo date('Y-m-d H:i:s') . " a Job is Pushed to the MQ"."<br>";
            }
        }
    }

    
    
    
    
    
    
    
    
    
}


























