<?php
// supervisor管理进程 
//https://blog.csdn.net/will5451/article/details/80434174
thinkphp-queue 是thinkphp 官方提供的一个消息队列服务，它支持消息队列的一些基本特性：

消息的发布，获取，执行，删除，重发，失败处理，延迟执行，超时控制等
队列的多队列， 内存限制 ，启动，停止，守护等
消息队列可降级为同步执行

think\Queue::push($job, $data = '', $queue = null) 
think\Queue::later($delay, $job, $data = '', $queue = null)
 两个方法，前者是立即执行，后者是在$delay秒后执行

php think queue:listen
php think queue:work --daemon（不加--daemon为执行单个任务）

//查看文档
php think queue:work -h
php think queue:listen -h

 创建 -> 推送 -> 消费 -> 删除 


work 命令是在脚本内部做循环，框架脚本在命令执行的初期就已加载完毕；
而listen模式则是处理完一个任务之后新开一个work进程，此时会重新加载框架脚本。
因此： work 模式的性能会比listen模式高。
注意：当代码有更新时，work 模式下需要手动去执行 php think queue:restart 命令重启队列来使改动生效；而listen 模式会自动生效,无需其他操作。


work 模式下的超时控制能力，实际上应该理解为 多个work 进程配合下的过期任务重发能力。
而 listen命令可以限制其创建的work子进程的超时时间。


work 命令的适用场景是：
    任务数量较多
    性能要求较高
    任务的执行时间较短
    消费者类中不存在死循环，sleep() ，exit() ,die() 等容易导致bug的逻


listen命令的适用场景是：
    任务数量较少
    #任务的执行时间较长(如生成大型的excel报表等)，
    任务的执行时间需要有严格限制

多模块
单模块项目推荐使用 app\job 作为任务类的命名空间
多模块项目可用使用 app\module\job 作为任务类的命名空间 也可以放在任意可以自动加载到的地方
多任务
如果一个任务类里有多个小任务的话，在发布任务时，需要用 任务的类名@方法名 如 app\lib\job\Job2@task1、app\lib\job\Job2@task2

使用方式：
	在生产者业务代码中：
	// 即时执行
	$isPushed = Queue::push($jobHandlerClassName, $jobDataArr, $jobQueueName);
	// 延迟 2 秒执行
	$isPushed = Queue::later( 2, $jobHandlerClassName, $jobDataArr, $jobQueueName);
	// 延迟到 2017-02-18 01:01:01 时刻执行
	$time2wait = strtotime('2017-02-18 01:01:01') - strtotime('now');	
	$isPushed = Queue::later($time2wait,$jobHandlerClassName, $jobDataArr, $jobQueueName);

	在消费者类中：
	// 重发，即时执行
	$job->release();
	// 重发，延迟 2 秒执行
	$job->release(2);
	// 延迟到 2017-02-18 01:01:01 时刻执行
	$time2wait = strtotime('2017-02-18 01:01:01') - strtotime('now');
	$job->release($time2wait);

	在命令行中：
	//如果消费者类的fire()方法抛出了异常且任务未被删除时，将自动重发该任务，重发时，会设置其下次执行前延迟多少秒,默认为0
	php think queue:work --delay 3  












