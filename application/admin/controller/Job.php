<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年12月1日 下午5:51:19
 */
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Queue;

class Job extends baseAdmin{
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


























