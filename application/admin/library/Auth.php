<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年9月23日 下午12:33:17
*/
namespace app\admin\library;
use app\admin\model\Admin;
use we\Random;
use we\Tree;
use think\Config;
use think\Cookie;
use think\Request;
use think\Session;
use think\Db;

class Auth extends \we\Auth{

    protected $_error = '';
    protected $requestUri = '';
    protected $breadcrumb = [];
    protected $logined = false; //登录状态
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __get($name)
    {
        return Session::get('admin.' . $name);
    }
    
    /**
     * 管理员登录
     *
     * @param   string  $username   用户名
     * @param   string  $password   密码
     * @param   int     $keeptime   有效时长
     * @return  boolean
     */
    public function login($username, $password, $keeptime = 0)
    {
        //         print_r(Request::instance()->param());die();
        //         $admin = Admin::get(['username' => $username]);
        //         print_r($admin);die();
        //         $adminModel = new Admin();
        $admin = Admin::where(['username' => $username])->find();
        //->field('id,username,nickname,email,avatar,status,errcount')
        if (!$admin)
        {
            $this->setError('Username is incorrect');
            return false;
        }
    
        if($admin->status == 0) {
            $this->setError('Account is not activated');
            return false;
        }
    
        if (Config::get('fastadmin.login_failure_retry') && $admin->loginfailure >= 10 && time() - $admin->updatetime < 86400)
        {
            $this->setError('Please try again after 1 day');
            return false;
        }

        $login_limit = Db::name('config')->where(['key' => 'login_limit'])->column('value')[0];
        $login_limit = json_decode($login_limit, true);
    
        if ($login_limit['login_limit'] == 1) {
            $error_count = $login_limit['error_count'];
            $login_time_limit = $login_limit['login_time_limit'];
            if($admin->errcount == $error_count) {
                if (time() - $admin->lockstarttime < $login_time_limit * 60 ) {
                    $this->setError('您登陆失败次数已经超过'.$error_count.',请'.$login_time_limit.'分钟后再尝试登陆');
                    return false;
                } else {
                    $admin->errcount = 0;
                    $admin->lockstarttime = '';
                    $admin->save();
                }
            }
        }
    
        if ($admin->password != md5(md5($password) . $admin->salt))
        {
            if ($login_limit['login_limit'] == 1) {
                $admin->errcount++;
                if ($admin->errcount == $error_count) {
                    $admin->lockstarttime = time();
                }
    
            }
    
            $admin->loginfailure++;
            $admin->save();
            $this->setError('Password is incorrect');
            return false;
        }
        if ($login_limit['login_limit'] == 1) {
            $admin->errcount = 0;
            $admin->lockstarttime = '';
        }
        $admin->loginfailure = 0;
        $admin->logintime = time();
        $admin->token = Random::uuid();
        $admin->save();
        $admin_info = $admin->toArray();
        unset($admin_info['password']);
        unset($admin_info['salt']);
        unset($admin_info['token']);
//         print_r($admin_info);die();
        Session::set("admin", $admin_info);
        $admin_info['group_id'] = $this->getGroupsIds($admin_info['id']);
        $admin_info['type'] = 'init';
        Session::set("admin_info", json_encode($admin_info));
        $this->keeplogin($keeptime);
        return true;
    }
    
    /**
     * 注销登录
     */
    public function logout()
    {
        $admin = Admin::get(intval($this->id));
        if (!$admin)
        {
            return true;
        }
        $admin->token = '';
        $admin->save();
        Session::delete("admin");
        Cookie::delete("keeplogin");
        return true;
    }
    
    /**
     * 自动登录
     * @return boolean
     */
    public function autologin()
    {
        $keeplogin = Cookie::get('keeplogin');
        if (!$keeplogin)
        {
            return false;
        }
        list($id, $keeptime, $expiretime, $key) = explode('|', $keeplogin);
        if ($id && $keeptime && $expiretime && $key && $expiretime > time())
        {
            $admin = Admin::get($id);
            if (!$admin || !$admin->token)
            {
                return false;
            }
            //token有变更
            if ($key != md5(md5($id) . md5($keeptime) . md5($expiretime) . $admin->token))
            {
                return false;
            }
            Session::set("admin", $admin->toArray());
            //刷新自动登录的时效
            $this->keeplogin($keeptime);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * 刷新保持登录的Cookie
     *
     * @param   int     $keeptime
     * @return  boolean
     */
    protected function keeplogin($keeptime = 0)
    {
        if ($keeptime)
        {
            $expiretime = time() + $keeptime;
            $key = md5(md5($this->id) . md5($keeptime) . md5($expiretime) . $this->token);
            $data = [$this->id, $keeptime, $expiretime, $key];
            Cookie::set('keeplogin', implode('|', $data), 86400 * 30);
            return true;
        }
        return false;
    }
    
    public function check($name, $uid = '', $relation = 'or', $mode = 'url')
    {
        return parent::check($name, $this->id, $relation, $mode);
    }
    
    /**
     * 检测当前控制器和方法是否匹配传递的数组
     *
     * @param array $arr 需要验证权限的数组
     */
    public function match($arr = [])
    {
        $request = Request::instance();
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr)
        {
            return FALSE;
        }
    
        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower($request->action()), $arr) || in_array('*', $arr))
        {
            return TRUE;
        }
    
        // 没找到匹配
        return FALSE;
    }
    
    /**
     * 检测是否登录
     *
     * @return boolean
     */
    public function isLogin()
    {
        if ($this->logined)
        {
            return true;
        }
        $admin = Session::get('admin');
        if (!$admin)
        {
            return false;
        }
        //判断是否同一时间同一账号只能在一个地方登录
        if (Config::get('fastadmin.login_unique'))
        {
            $my = Admin::get($admin['id']);
            if (!$my || $my['token'] != $admin['token'])
            {
                return false;
            }
        }
        $this->logined = true;
        return true;
    }
    
    /**
     * 获取当前请求的URI
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }
    
    /**
     * 设置当前请求的URI
     * @param string $uri
     */
    public function setRequestUri($uri)
    {
        $this->requestUri = $uri;
    }
    
    public function getGroups($uid = null)
    {
        $uid = is_null($uid) ? $this->id : $uid;
        return parent::getGroups($uid);
    }
    
    public function getRuleList($uid = null)
    {
        $uid = is_null($uid) ? $this->id : $uid;
        return parent::getRuleList($uid);
    }
    
    public function getUserInfo($uid = null)
    {
        $uid = is_null($uid) ? $this->id : $uid;
    
        return $uid != $this->id ? Admin::get(intval($uid)) : Session::get('admin');
    }
    
    public function getRuleIds($uid = null)
    {
        $uid = is_null($uid) ? $this->id : $uid;
        return parent::getRuleIds($uid);
    }
    
    public function isSuperAdmin()
    {
        return in_array('*', $this->getRuleIds()) ? TRUE : FALSE;
    }
    
    /**
     * 获取管理员所属于的分组ID
     * @param int $uid
     * @return array
     */
    public function getGroupIds($uid = null)
    {
        $groups = $this->getGroups($uid);
        $groupIds = [];
        foreach ($groups as $K => $v)
        {
            $groupIds[] = (int) $v['group_id'];
        }
        return $groupIds;
    }
    
    /**
     * 取出当前管理员所拥有权限的分组
     * @param boolean $withself 是否包含当前所在的分组
     * @return array
     */
    public function getChildrenGroupIds($withself = false)
    {
        //取出当前管理员所有的分组
        $groups = $this->getGroups();
        $groupIds = [];
        foreach ($groups as $k => $v)
        {
            $groupIds[] = $v['id'];
        }
        // 取出所有分组
        $groupList = \app\admin\model\AuthGroup::select();//::where(['status' => '1'])
        //         \app\admin\model\AuthGroup::select();
        $objList = [];
        foreach ($groups as $K => $v)
        {
            if ($v['rules'] === '*')
            {
                $objList = $groupList;
                break;
            }
            // 取出包含自己的所有子节点
            $childrenList = Tree::instance()->init($groupList)->getChildren($v['id'], true);
            $obj = Tree::instance()->init($childrenList)->getTreeArray($v['pid']);
            $objList = array_merge($objList, Tree::instance()->getTreeList($obj));
        }
        $childrenGroupIds = [];
        foreach ($objList as $k => $v)
        {
            $childrenGroupIds[] = $v['id'];
        }
        if (!$withself)
        {
            $childrenGroupIds = array_diff($childrenGroupIds, $groupIds);
        }
        return $childrenGroupIds;
    }
    
    /**
     * 取出当前管理员所拥有权限的管理员
     * @param boolean $withself 是否包含自身
     * @return array
     */
    public function getChildrenAdminIds($withself = false)
    {
        $childrenAdminIds = [];
        if (!$this->isSuperAdmin())
        {
            $groupIds = $this->getChildrenGroupIds(false);
            $authGroupList = \app\admin\model\AuthGroupAccess::
            field('uid,group_id')
            ->where('group_id', 'in', $groupIds)
            ->select();
    
            foreach ($authGroupList as $k => $v)
            {
                $childrenAdminIds[] = $v['uid'];
            }
        }
        else
        {
            //超级管理员拥有所有人的权限
            $childrenAdminIds = Admin::column('id');
        }
        if ($withself)
        {
            if (!in_array($this->id, $childrenAdminIds))
            {
                $childrenAdminIds[] = $this->id;
            }
        }
        else
        {
            $childrenAdminIds = array_diff($childrenAdminIds, [$this->id]);
        }
        return $childrenAdminIds;
    }
    
    /**
     * 获得面包屑导航
     * @param string $path
     * @return array
     */
    public function getBreadCrumb($path = '')
    {
        $modulename = $this->request->module();
        if ($this->breadcrumb || !$path)
            return $this->breadcrumb;
        $path_rule_id = 0;
        //         print_r($this->rules);
        //         echo $path;
        foreach ($this->rules as $rule)
        {
            $path_rule_id = $rule['name'] == $path ? $rule['id'] : $path_rule_id;
        }
        if ($path_rule_id)
        {
            $this->breadcrumb = Tree::instance()->init($this->rules)->getParents($path_rule_id, true);
            //             print_r($this->breadcrumb);
            foreach ($this->breadcrumb as $k => &$v)
            {
    
                if(strpos($v['name'], '/index')){
                    unset($this->breadcrumb[$k]);continue;
                }else{
                    $v['url'] = '/'.$modulename.'/'.str_replace('.', '/', $v['name']);
                    $v['title'] = __($v['title']);
                }
            }
        }
        //             print_r($this->breadcrumb);
        return $this->breadcrumb;
    }
    
    /**
     * 获取左侧菜单栏
     *
     * @param array $params URL对应的badge数据
     * @return string
     */
    public function getSidebar($params = [], $fixedPage = 'dashboard')
    {

            $colorArr = ['red', 'green', 'yellow', 'blue', 'teal', 'orange', 'purple'];
            $colorNums = count($colorArr);
            $badgeList = [];
            $module = request()->module();
            // 生成菜单的badge
            foreach ($params as $k => $v)
            {
        
                $url = $k;
        
                if (is_array($v))
                {
                    $nums = isset($v[0]) ? $v[0] : 0;
                    $color = isset($v[1]) ? $v[1] : $colorArr[(is_numeric($nums) ? $nums : strlen($nums)) % $colorNums];
                    $class = isset($v[2]) ? $v[2] : 'label';
                }
                else
                {
                    $nums = $v;
                    $color = $colorArr[(is_numeric($nums) ? $nums : strlen($nums)) % $colorNums];
                    $class = 'label';
                }
                //必须nums大于0才显示
                if ($nums)
                {
                    $badgeList[$url] = '<small class="' . $class . ' pull-right bg-' . $color . '">' . $nums . '</small>';
                }
            }
        
            // 读取管理员当前拥有的权限节点
            $userRule = $this->getRuleList();
        
            $select_id = 0;
//             $pinyin = new \Overtrue\Pinyin\Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
            
        if(!cache('ruleList2')){      
            // 必须将结果集转换为数组
            $ruleList1 = collection(\app\admin\model\AuthRule::where('status', '1')->where('ismenu', 1)->order('weigh', 'desc')->select())->toArray();
            $pid_ids = Db::name('AuthRule')->column('pid','id');
            cache('ruleList2', $ruleList1);
            cache('pid_ids', $pid_ids);
        }

        $ruleList = cache('ruleList2');
//         print_r($ruleList);die();
        foreach ($ruleList as $k => &$v)
        {
            if($v['pid'] == 0) {
//                 unset($v);
            }
            if (!in_array($v['name'], $userRule))
            {
                unset($ruleList[$k]);
                continue;
            }
            $select_id = $v['name'] == $fixedPage ? $v['id'] : $select_id; #控制台
            $v['url'] = '/' . $module . '/' . $v['name'];
            $v['badge'] = isset($badgeList[$v['name']]) ? $badgeList[$v['name']] : ''; #hot、new
//             $v['py'] = $pinyin->abbr($v['title'], ''); #Return first letters.
//             $v['pinyin'] = $pinyin->permalink($v['title'], '');#返回拼音
            $v['title'] = __($v['title']);
        }
        if(!cache('ruleList3')) {
            cache('ruleList3', $ruleList);
        }
        $arr_ = Category::unlimiteForLayer($ruleList,'child');
        foreach ($arr_ as $k3 => $v3){
            $arr[$v3['id']] = $v3['child'];
        }

        $html = [];
        $nav_url = [];
        $i = 1;
        $menu_active = "";
        $nav_id = intval(input('nav_id'));
//         echo $nav_id;
        $nav_pid = intval(input('nav_pid'));
//         $ruleList2 = $ruleList;

        foreach ($arr as $k4 => $v4){
            foreach ($v4 as $k => $v) {
                if($k == 0 && $nav_pid) {
                    $expand = "layui-nav-itemed";
                    $active = "layui-this";
                } else {
                    $expand = "";
                    $active = "";
                }
                if(cache('pid_ids')[$nav_id] == $v['id']) {
                    $expand = "layui-nav-itemed";
                }
                if($i == 1) {
                    $html[$k4] .= "<li data-name='{$v['pinyin']}' data-id='{$k4}' class='layui-nav-item {$expand}'>";
                } else {
                    $html[$k4] .= "<li data-name='{$v['pinyin']}' data-id='{$k4}' class='layui-nav-item {$expand}'>";
                }
                $expand = "";
                if(isset($v['child'])){
                    $html[$k4] .= "<a href='javascript:;' lay-tips='{$v['title']}'>";
                } else {
                    $html[$k4] .= "<a href='{$v['lay-href']}' lay-tips='{$v['title']}'>";
                }
                $html[$k4] .= "<i class='{$v['icon']}'></i><cite>{$v['title']}</cite></a>";
                
                if(isset($v['child'])){
                    $html[$k4] .= '<dl class="layui-nav-child">';
                    foreach ($v['child'] as $k1 => $v1) {
                        if(isset($v1['child'])) {
                            $html[$k4] .= "<dd data-name='{$v1['title']}'>";
                            $html[$k4] .= "<a href='javascript:;' lay-tips='{$v1['title']}'>{$v1['title']}</a>";
                            $html[$k4] .= '<dl class="layui-nav-child">';
                            foreach ($v1['child'] as $k2 => $v2) {
                                if($v2['id'] == $nav_id) {
                                    $menu_active = "layui-this";
                                } else {
                                    $menu_active = "";
                                }

                                if($k2 == 0) {
                                    $nav_url[$k4] = $nav_url[$k4]?$nav_url[$k4]:$v2['url'];
                                    $html[$k4] .= "<dd data-name='{$v2['pinyin']}' class='{$active} {$menu_active}'><a href=".url($v2['url'],['nav_id' => $v2['id'],'nav_pid' => $k4])."><i class='{$v2['icon']}'></i>{$v2['title']}</a></dd>";
                                } else {
                                    $html[$k4] .= "<dd data-name='{$v2['pinyin']}' class='{$menu_active}'><a href=".url($v2['url'],['nav_id' => $v2['id'],'nav_pid' => $k4])."><i class='{$v2['icon']}'></i>{$v2['title']}</a></dd>";
                                }
                               
                            }
                            $html[$k4] .= '</dl></dd>';
                        }else {
                            if($v1['id'] == $nav_id) {
                                $menu_active = "layui-this";
                            } else {
                                $menu_active = "";
                            }
                            if($k1 == 0) {
                                $nav_url[$k4] = $nav_url[$k4]?$nav_url[$k4]:$v1['url'];
                                $html[$k4] .= "<dd class='{$menu_active} {$menu_active}' data-name='{$v1['pinyin']}'><a href=".url($v1['url'],['nav_id' => $v1['id'],'nav_pid' => $k4])."><i class='{$v1['icon']}'></i>{$v1['title']}</a></dd>";
                            } else {
                                $html[$k4] .= "<dd class='{$menu_active}' data-name='{$v1['pinyin']}'><a href=".url($v1['url'],['nav_id' => $v1['id'],'nav_pid' => $k4])."><i class='{$v1['icon']}'></i>{$v1['title']}</a></dd>";
                            }
                        }
                    }
                    $html[$k4] .= '</dl>';
                }
                $html[$k4] .= '</li>';           
            }
            $i++;
        }
//         print_r($ruleList2);die();
//         print_r($ruleList);die();
        
        return [$html,$nav_url,$arr_,$ruleList];
        // 构造菜单数据
        Tree::instance()->init($ruleList);
        #@clas: li.class="treeview"表示一级导航有下拉，,第一层ul.class="sidebar-menu",第二级ul.class="treeview-menu"
        #@addtabs:  ref=addtabs
        #@id: scan_auth_rule对应id
        #@url: url
        #@py:拼音首字母
        #@pinyin：拼音全写
        #@icon：scan_auth_rule对应icon
        #@title:title
        #@childlist：子下拉
        #@caret：<i class="fa fa-angle-left"></i>
        #@badge：#hot、new
    
    
        $menu = Tree::instance()->getTreeMenu(0, '<li class="@class">
                                                    <a href="@url@addtabs" addtabs="@id" url="@url" py="@py" pinyin="@pinyin">
                                                        <i class="@icon"></i> 
                                                        <span>@title</span> 
                                                        <span class="pull-right-container">@caret @badge</span>
                                                    </a> @childlist
                                                   </li>',
                                                $select_id, 
                                                '', 
                                                'ul', 
                                                'class="treeview-menu"');
                                                
        return $menu;
    }
    
    /**
     * 设置错误信息
     *
     * @param $error 错误信息
     */
    public function setError($error)
    {
        $this->_error = $error;
        return $this;
    }
    
    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->_error ? __($this->_error) : '';
    }
}