<?php
/**
* @author: qxn
* @E-mial: 2423859713@qq.com
* @date: 2018年11月23日 下午4:43:14
* 导入挂件
*/
namespace app\admin\widget;

use app\common\controller\baseAdmin;

class Import extends baseAdmin{
    
    public function Import($import_url = false){
        $this->assign('import_url', $import_url);
        return $this->fetch('widget/import');
    }
    
    public function importSuning($import_url = false){
        $this->assign('import_url', $import_url);
        return $this->fetch('widget/importSuning');
    }
}