<?php
/*
 *@author: qinxuening
 *@E-mail: 2423859713@qq.com
 *@date: 2018年12月16日 下午1:33:45
 *三个月前还是
 */
namespace app\admin\controller;
use app\common\controller\baseAdmin;
use think\Db;
use think\Cache;
use we\Http;

class Addon extends baseAdmin{
    protected $table;
    protected $table_name;
    protected $status;
    protected $excel_title;
    
    public function _initialize()
    {
        parent::_initialize();
        $this->table = Db::name('addon');
        $this->table_name = 'addon';
        $this->status = [
            'status' => ['0'=>'禁用','1'=>'启用'],
        ];
        $this->excel_title = '插件管理报表';
    }
    
    public function index() {
        if ($this->request->isAjax())
        {
            $offset = (int)$this->request->get("offset");
            $limit = (int)$this->request->get("limit");
            $filter = $this->request->get("filter");
            $search = $this->request->get("search");
            $search = htmlspecialchars(strip_tags($search));
            
            $onlineaddons = Cache::get("onlineaddons");
            if (!is_array($onlineaddons)) {
                $onlineaddons = [];
                $result = Http::sendRequest(config('fastadmin.api_url') . '/addon/index');
                if ($result['ret']) {
                    $json = json_decode($result['msg'], TRUE);
                    $rows = isset($json['rows']) ? $json['rows'] : [];
                    foreach ($rows as $index => $row) {
                        $onlineaddons[$row['name']] = $row;
                    }
                }
                Cache::set("onlineaddons", $onlineaddons, 600);
            }
            $filter = (array)json_decode($filter, true);
            $addons = get_addon_list();
            $i = 1;
            $list = [];
            foreach ($addons as $k => $v) {
                if ($search && stripos($v['name'], $search) === FALSE && stripos($v['intro'], $search) === FALSE)
                    continue;
                    
                    if (isset($onlineaddons[$v['name']])) {
                        $v = array_merge($v, $onlineaddons[$v['name']]);
                    } else {
                        $v['category_id'] = 0;
                        $v['flag'] = '';
                        $v['banner'] = '';
                        $v['image'] = '';
                        $v['donateimage'] = '';
                        $v['demourl'] = '';
                        $v['price'] = '0.00';
                        $v['screenshots'] = [];
                        $v['releaselist'] = [];
                    }
                    $v['id'] = $i++;
                    $v['describe'] = $v['intro'];
                    $v['status'] = $v['state'];
                    $v['url'] = addon_url($v['name']);
                    $v['createtime'] = filemtime(ADDON_PATH . $v['name']);
                    if ($filter && isset($filter['category_id']) && is_numeric($filter['category_id']) && $filter['category_id'] != $v['category_id']) {
                        continue;
                    }
                    $list[] = $v;
            }
            $total = count($list);
            if ($limit) {
                $list = array_slice($list, $offset, $limit);
            }
            $result = ['code' => 0, 'count' => $total, 'status' => 'success', 'data' => $list,'msg' => '获取成功'];
            $callback = $this->request->get('callback') ? "jsonp" : "json";
            return $callback($result);
        } else {
            return $this->view->fetch();
        }
    }
    
    
    
    
    
    
    
    
    
    
}