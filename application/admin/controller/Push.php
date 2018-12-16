<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年10月14日 下午1:33:45
*/
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Db;
use GatewayClient\Gateway;

class Push extends baseAdmin{
    protected $status;
    protected $status_c;
    protected $table;
    protected $table_name;
    protected $excel_title;
    
    public function _initialize()
    {
        parent::_initialize();
        $this->table = Db::name('push');
        $this->table_name = 'push';
        $this->excel_title = '推送管理报表';
        $this->status = [
            'status' => ['0'=>'禁用','1'=>'启用'],
            'is_time_push' => ['0'=>'禁用','1'=>'启用'],
        ];
        $this->status_c = ['禁用'=>'1','启用'=>'1'];
    }

    
    /**
     * 推送发送
     */
    public function push() {
        $list = Db::name('push')->field('id,title,content')->where(['id' => intval(input('ids'))])->find();
        $list['time'] = date('Y-m-d H:i:s');
        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
        Gateway::$registerAddress = '127.0.0.1:'.config('worker.register_port');
        // 向任意uid的网站页面发送数据
        //Gateway::sendToUid($this->uid, $message);
//         $result = Gateway::sendToAll(json_encode($list));
        $result = Gateway::sendToUid(session('admin')['id'], json_encode($list)); // 发送给指定用户
        return json(['code' => 1, 'status' => 'success', 'data'=>Gateway::getAllUidList(), 'msg' => '推送成功','url'=>'']);
    }
    
    /**
     * 导入数据
     */
    public function import(){
        set_time_limit ( 0 ); // 脚本执行没有时间限
        ini_set("memory_limit","-1");
        ini_set('max_execution_time', '0');
        if (IS_AJAX) {
            $filepath = input('filepath');
            $data = import_excel($filepath, 0);
            if(is_array($data) && count($data) >0){
                if ($data[0]['定时推送时间'] && $data[0]['概要'] && $data[0]['是否定时推送'] && $data[0]['状态']){
                    $i = $j = 0;
                    myLog('################开始导入###############');
                    foreach ($data as $k => $v) {
                        $arr['title'] = $v['概要'];
                        $arr['time_push'] = trim($v['定时推送时间']);
                        $arr['status'] = $this->status_c[$v['状态']];
                        $arr['is_time_push'] = $this->status_c[$v['是否定时推送']];
                        $arr['createtime'] = date('Y-m-d H:i:s', time());
                        $result = Db::name('push')->insert($arr);
                        if($result) {
                            $i++;
                        } else {
                            $j++;
                            myLog('---------------导入失败数据---------------');
                            myLog(json_encode($v));
                        }
                    } 
                    return json(['code' => 1, 'status' => 'success', 'msg' => '导入数据成功', 'data' => [$i,$j]]);
                } else {
                    return json(['code' => -2, 'status' => 'error', 'msg' => '数据格式不正确！']);
                }
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => '导入数据为空']);
            }
        }
        return $this->view->fetch();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}