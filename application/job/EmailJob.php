<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年12月1日 下午5:51:19
 */
namespace app\job;
use think\queue\Job;
use think\Controller;

class EmailJob extends Controller{
    
    /**
     * yum install jq -y
     * 队列调用
     * @param Job $job
     * @param unknown $data
     */
    public function fire(Job $job, $data){
        $isJobDone = $this->sendMail($data);
        if ($isJobDone) {
            $str = "Job is Done!";
            $job->delete();
        } else {
            $str = "Job is Filed!";
            if ($job->attempts() > 3) {
                $str = "Job is Filed,Delete!";
                $job->delete();
            } else {
                $job->release(); //重发任务
                $str = "Job is release!-----------" . $job->attempts() . "!";
            }
        }
        systemLog($str);
    }
    
    /**
     * 发送邮件
     * @param $data
     * @return bool
     */
    private function sendMail($data)
    {
        $msg = "账号激活邮件\r\n\r\n";
        $msg .= "欢迎您注册xxx网站,您的请点击一下连接激活您的账号!....";
        try {
            systemLog($msg);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
}
