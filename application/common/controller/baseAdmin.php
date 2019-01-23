<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年9月19日 下午11:13:08
*/
 
namespace app\common\controller;
use app\admin\library\Auth;
use think\Controller;
use think\Lang;
use think\Hook;
use think\Session;
use think\Db;
use we\Tree;
use app\admin\library\Category;

class baseAdmin extends Controller{
    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = [];

    /**
     * 布局模板
     * @var string
     */
    protected $layout = '';//default

    /**
     * 权限控制类
     * @var Auth
     */
    protected $auth = null;

    /**
     * 快速搜索时执行查找的字段
     */
    protected $searchFields = 'id';

    /**
     * 是否是关联查询
     */
    protected $relationSearch = false;

    /**
     * 是否开启数据限制
     * 支持auth/personal
     * 表示按权限判断/仅限个人 
     * 默认为禁用,若启用请务必保证表中存在admin_id字段
     */
    protected $dataLimit = false;

    /**
     * 数据限制字段
     */
    protected $dataLimitField = 'admin_id';

    /**
     * 数据限制开启时自动填充限制字段值
     */
    protected $dataLimitFieldAutoFill = true;

    /**
     * 是否开启Validate验证
     */
    protected $modelValidate = false;

    /**
     * 是否开启模型场景验证
     */
    protected $modelSceneValidate = false;

    /**
     * Multi方法可批量修改的字段
     */
    protected $multiFields = 'status';

    /**
     * 导入文件首行类型
     * 支持comment/name
     * 表示注释或字段名
     */
    protected $importHeadType = 'comment';
    
    use \app\admin\library\traits\Base;
    public function _initialize()
    {
        switch (input('lang')) {
            case 'zh-cn':
                cookie('think_var', 'zh-cn');
            ;
            break;
            case 'en-us':
                cookie('think_var', 'en-us');
                ;
                break;
            default:
                ;
            break;
        }
        $modulename = $this->request->module();
        $controllername = strtolower($this->request->controller());
        $actionname = strtolower($this->request->action());
        
        $path = str_replace('.', '/', $controllername) . '/' . $actionname;
        
        // 定义是否AJAX请求
        !defined('IS_AJAX') && define('IS_AJAX', $this->request->isAjax());
        
        $this->auth = Auth::instance();
        
        // 设置当前请求的URI
        $this->auth->setRequestUri($path);
        // 检测是否需要验证登录
        if (!$this->auth->match($this->noNeedLogin))
        {
            //检测是否登录
            if (!$this->auth->isLogin())
            {
                Hook::listen('admin_nologin', $this);
                $url = Session::get('referer');
                $url = $url ? $url : $this->request->url();
                $this->redirect(url('admin/index/login'));
            }
            // 判断是否需要验证权限
            if (!$this->auth->match($this->noNeedRight))
            {
                // 判断控制器和方法判断是否有对应权限
                if (!$this->auth->check($path))
                {
                    Hook::listen('admin_nopermission', $this);
                    $this->error(__('You have no permission'), '');
                }
            }
        }
        
        // 如果有使用模板布局
        if ($this->layout)
        {
            $this->view->engine->layout('layout/' . $this->layout);
        }
        
        /**
         * 缓存导航、menu
         */
        //左侧菜单
        $menulist = $this->auth->getSidebar([
            'dashboard' => '',//hot
            'addon'     => ['new', 'red', 'badge'],
            'auth/rule' => '',//__('Menu')
            'general'   => ['new', 'purple'],
        ], $this->view->site['fixedpage']);
        
        $action = $this->request->request('action');
        if ($this->request->isPost())
        {
            if ($action == 'refreshmenu')
            {
                $this->success('', null, ['menulist' => $menulist[0]]);
            }
        }
        if(!cache('menulist2')){
            $nav_list = Db::name('AuthRule')->where(['ismenu' => 1, 'status' => 1, 'pid' => 0])->column('title,weigh','id');
            
            foreach ($menulist[2] as $k => $v) {
                $arr_nav[$v['id']] = $v;
            }
            
            $auth_rule_ids = Db::name('AuthRule')->column('name','id');
            $auth_rule_ids = array_flip($auth_rule_ids);
            cache('menulist2', $arr_nav);
            cache('nav_list',$nav_list);
            cache('nav_url',$menulist[1]);
            cache('auth_rule_ids',$auth_rule_ids);
            cache('ruleList',$menulist[3]);
        }
        
        foreach (cache('nav_list') as $k =>$v) {
            $max_weigh[] = $v['weigh'];
        } 
        
        $id = intval(input('nav_id'));
        $pid = intval(input('nav_pid'));

        if($id){
            $arr_ = Category::getParent(cache('ruleList3'),$id);
            $pid1 = $arr_[0]['id'];
        }
        
        if((isset($_GET['nav_pid']) && !in_array($pid, array_keys(cache('nav_url')))) 
            || (isset($_GET['nav_id']) && !$menulist[0][$pid1])
            ) {
            $this->redirect("/admin/index/index");
        }

        $this->assign('max_weigh', $max_weigh);
        $this->view->assign('nav_list', cache('menulist2'));//rule

        $this->view->assign('menulist1', $menulist[0][$pid1]);//menu
        $this->view->assign('nav_url', cache('nav_url'));  //导航
        // 语言检测
        $lang = strip_tags(Lang::detect());
        // 配置信息后
        Hook::listen("config_init", $config);
        //加载当前控制器语言包
        $this->loadlang($controllername);
        //渲染配置信息
        $this->assign('config', $config);

        $this->assign('this_url', '/'.$modulename.'/'.$controllername.'/'.$actionname);
        //渲染权限对象
        $this->assign('auth', $this->auth);
        $this->assign('msectime', msectime());
        //渲染管理员对象
        $this->assign('admin', Session::get('admin'));
    }
    
    
    public function _empty(){
        $this->redirect("/admin/index/index");
        //return json(['code'=>-1,'status'=>'error','info'=>'非法访问']);
    }
    
    /**
     * 渲染配置信息
     * @param mixed $name 键名或数组
     * @param mixed $value 值
     */
    protected function assignconfig($name, $value = '')
    {
        $this->view->config = array_merge($this->view->config ? $this->view->config : [], is_array($name) ? $name : [$name => $value]);
    }
    
    /**
     * 加载语言文件
     * @param string $name
     */
    protected function loadlang($name)
    {
        Lang::load(APP_PATH . $this->request->module() . '/lang/' . Lang::detect() . '/' . str_replace('.', '/', $name) . '.php');
    }
    
    protected function buildparams(){
        $filter = $this->request->param("filter", '');
        $op = $this->request->param("op", '', 'trim');
        $filter = json_decode($filter, TRUE);
        $op = json_decode($op, TRUE);
        $filter = $filter ? $filter : [];
        $sort = $this->request->param("sort", "id");
        $order = $this->request->param("order", "DESC");
        $offset = $this->request->param("offset", 0);
        $limit = $this->request->param("limit", 0);

        $where = [];
        $tableName = '';

        foreach ($filter as $k => $v)
        {
            if($v === '') continue;
            $sym = isset($op[$k]) ? $op[$k] : '=';
            if (stripos($k, ".") === false)
            {
                $k = $tableName . $k;
            }
            $v = !is_array($v) ? trim($v) : $v;
            $sym = strtoupper(isset($op[$k]) ? $op[$k] : $sym);
            switch ($sym)
            {
                case '=':
                case '!=':
                    $where[] = [$k, $sym, (string) $v];
                    break;
                case 'LIKE':
                case 'NOT LIKE':
                case 'LIKE %...%':
                case 'NOT LIKE %...%':
                    $where[] = [$k, trim(str_replace('%...%', '', $sym)), "%{$v}%"];
                    break;
                case '>':
                case '>=':
                case '<':
                case '<=':
                    $where[] = [$k, $sym, intval($v)];
                    break;
                case 'FINDIN':
                case 'FIND_IN_SET':
                    $where[] = "FIND_IN_SET('{$v}', `{$k}`)";
                    break;
                case 'IN':
                case 'IN(...)':
                case 'NOT IN':
                case 'NOT IN(...)':
                    $where[] = [$k, str_replace('(...)', '', $sym), is_array($v) ? $v : explode(',', $v)];
                    break;
                case 'BETWEEN':
                case 'NOT BETWEEN':
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr))
                        continue;
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '')
                    {
                        $sym = $sym == 'BETWEEN' ? '<=' : '>';
                        $arr = $arr[1];
                    }
                    else if ($arr[1] === '')
                    {
                        $sym = $sym == 'BETWEEN' ? '>=' : '<';
                        $arr = $arr[0];
                    }
                    $where[] = [$k, $sym, $arr];
                    break;
                case 'RANGE':
                case 'NOT RANGE':
                    $v = str_replace(' - ', ',', $v);
                    $arr = array_slice(explode(',', $v), 0, 2);
                    if (stripos($v, ',') === false || !array_filter($arr))
                        continue;
                    //当出现一边为空时改变操作符
                    if ($arr[0] === '')
                    {
                        $sym = $sym == 'RANGE' ? '<=' : '>';
                        $arr = $arr[1];
                    }
                    else if ($arr[1] === '')
                    {
                        $sym = $sym == 'RANGE' ? '>=' : '<';
                        $arr = $arr[0];
                    }
                    $where[] = [$k, str_replace('RANGE', 'BETWEEN', $sym) . ' time', $arr];
                    break;
                case 'LIKE':
                case 'LIKE %...%':
                    $where[] = [$k, 'LIKE', "%{$v}%"];
                    break;
                case 'NULL':
                case 'IS NULL':
                case 'NOT NULL':
                case 'IS NOT NULL':
                    $where[] = [$k, strtolower(str_replace('IS ', '', $sym))];
                    break;
                default:
                    break;
            }
        }

        $where = function($query) use ($where) {
            foreach ($where as $k => $v)
            {
                if (is_array($v))
                {
                    call_user_func_array([$query, 'where'], $v);
                }
                else
                {
                    $query->where($v);
                }
            }
        };
        return [$where, $sort, $order, $offset, $limit];
    }
    
    /**
     * 导入数据
     */
    public function import() {
        return $this->view->fetch();
    }
    
    

    
    
    
}