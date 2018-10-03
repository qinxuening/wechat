<?php
/*
 *@author: qinxuening
 *@E-mail: 2423859713@qq.com
 *@date: 2018年9月19日 下午11:13:08
 */
namespace app\admin\controller;
use app\common\controller\baseAdmin;

class Index extends baseAdmin {
    
    public function index() {
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
                $this->success('', null, ['menulist' => $menulist]);
            }
        }
//         dump($menulist);die();
        $this->view->assign('menulist', $menulist);
        $this->view->assign('title', __('Home'));
        return $this->view->fetch();
    }
    
    
    
}