<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年10月14日 下午1:33:45
*/
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Db;

class Push extends baseAdmin{

    /**
     * 推送列表
     */
    public function index($page = 0, $limit = 10) {
        if ($this->request->isAjax())
        {
            $count = Db::name('push')->count('*');
            $list = Db::name('push')->page($page,$limit)->select();
            return json(['code' => 0, 'count' => $count, 'data' => $list,'msg' => '获取成功']);
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
            $data['status'] = $data['status'] == 'on' ? '1' : 0;
            $data['is_time_push'] = $data['is_time_push'] == 'on' ? '1' : 0;
            if($id) {
                $result = Db::name('push')->where(['id' => $id])->update($data);
            } else {
                unset($data['id']);
                $result = Db::name('push')->where(['id' => $id])->insert($data);
            }
            
            if(false !== $result){
                return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功']);
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => '非法操作']);
            }
        } 
        $list = Db::name('push')->where(['id' => $ids])->find();
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
            $count = Db::name('push')->where('id', 'in', explode(',', $ids))->delete();
            if ($count)
            {
                return json(['code' => 1, 'status' => 'success', 'msg' => '删除成功']);
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => '删除失败']);
            }
        }
        return json(['code' => -2, 'status' => 'error', 'msg' => '非法操作']);
    }
    
    /**
     * 推送发送
     */
    public function push() {
        return json(['code' => 1, 'status' => 'success', 'msg' => '推送成功']);
    }
    
    
    
    
}