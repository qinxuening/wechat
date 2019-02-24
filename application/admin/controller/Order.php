<?php
/**
* @author: qxn
* @E-mial: 2423859713@qq.com
* @date: 2018年11月15日 下午5:51:19
*/
namespace app\admin\controller;
use app\common\controller\baseAdmin;

class Order extends baseAdmin{
    use \app\admin\library\traits\Upload;
    public function _initialize(){
        parent::_initialize();
    }

    public function index() {
        return $this->view->fetch();
    }

    /**
     * 获取省份
     */
    public function getAreaId() {
         return $this->view->fetch();
    }

    /**
     * 下单关怀
     */
    public function order_care() {
        return $this->view->fetch();
    }

    public function task_add() {
        return $this->view->fetch();
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

    /**
     * 短信测试发送记录
     * @return string
     */
    public function sms_record() {
        return $this->view->fetch();
    }




























}