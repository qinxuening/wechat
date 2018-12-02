<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年12月1日 下午5:51:19
 */
namespace app\job;
use think\queue\Job;
use think\Controller;
use think\Db;

class EmailJob extends Controller{
    
    /**
     * yum install jq -y
     * 队列调用
     * @param Job $job
     * @param unknown $data
     */
    public function fire(Job $job, $data){
        $rule = Db::name('auth_rule')->select();
        $rule = "'".json_encode($rule)."' | jq .";
        $str = "'".json_encode($data)."' | jq .";
        system('echo '.$dir.">/tmp/queue.log"); // 命令行执行，记录日志
        system('echo '.date('Y-m-d H:i:s').">/tmp/queue.log"); // 命令行执行，记录日志
        system('echo '.$str.">/tmp/queue.log"); // 命令行执行，记录日志
        $isJobDone = $this->sendMail($data);
        if ($isJobDone) {
            print("<info>Job is Done! "."</info>\n");
            $job->delete();
        } else {
            print("<warn>Job is Filed!" . "</warn>\n");
            if ($job->attempts() > 3) {
                print("<warn>Job is Filed!,Delete" . "</warn>\n");
                $job->delete();
            } else {
                $job->release(); //重发任务
                print("<warn>Job is release!-----------" . $job->attempts() . "!</warn>\n");
            }
        }
    }
    
    /**
     * 发送邮件
     * @param $data
     * @return bool
     */
    private function sendMail($data)
    {
        $this->myLog(json_encode($data));
        $title = '账号激活邮件';
        $msg = '欢迎您注册xxx网站,您的请点击一下连接激活您的账号!....';
        try {
            print("<info>".$msg."</info>\n");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public  function myLog($str) {
        $dir = getcwd(). '/public/logs/';
        if(!is_dir($dir)) {
            $flag =  mkdir($dir, 0777, true);
            dump($flag);
            chmod($dir, 0777);

        }

        $file = $dir . date('Ymd') . '.log.txt';
        $fp = fopen($file, 'a+');

        if (flock($fp, LOCK_EX)) {
            $content = "[" . date('Y-m-d H:i:s') . "]\r\n";
            $content .= $str . "\r\n\r\n";
            fwrite($fp, $content);
            flock($fp, LOCK_UN);
            fclose($fp);
            chmod($file, 0777);
        } else {
            fclose($fp);
        }
    }
}
