<?php
/*
*@author: qinxuening
*@E-mail: 2423859713@qq.com
*@date: 2018年9月22日 下午10:12:58
*/
use app\common\model\Category;
use we\Form;
use we\Tree;
use think\Db;
use think\Lang;
Lang::setAllowLangList(['zh-cn','en-us']);
if (!function_exists('build_select')) {

    /**
     * 生成下拉列表
     * @param string $name
     * @param mixed $options
     * @param mixed $selected
     * @param mixed $attr
     * @return string
     */
    function build_select($name, $options, $selected = [], $attr = [], $disabled_id = "",$table = "")
    {
        $options = is_array($options) ? $options : explode(',', $options);
        $selected = is_array($selected) ? $selected : explode(',', $selected);
        return Form::select($name, $options, $selected, $attr, $disabled_id,$table);
    }
}

if (!function_exists('build_radios')) {

    /**
     * 生成单选按钮组
     * @param string $name
     * @param array $list
     * @param mixed $selected
     * @return string
     */
    function build_radios($name, $list = [], $selected = null,$disabled = null)
    {
//                 print_r($list);
        $html = [];
        $selected = is_null($selected) ? key($list) : $selected; //key() 函数返回数组内部指针当前指向元素的键名
        $selected = is_array($selected) ? $selected : explode(',', $selected);
        foreach ($list as $k => $v) {
//             $html[] = sprintf(Form::label("{$name}-{$k}", "%s {$v}"), Form::radio($name, $k, in_array($k, $selected), ['id' => "{$name}-{$k}",$disabled]));
            $html[] = sprintf(Form::label("{$name}-{$k}", "%s"), Form::radio($name, $k, in_array($k, $selected), ['id' => "{$name}-{$k}",'title' => $v,$disabled]));
        }
        return '<div class="radio">' . implode(' ', $html) . '</div>';
    }
}

if (!function_exists('build_checkboxs')) {

    /**
     * 生成复选按钮组
     * @param string $name
     * @param array $list
     * @param mixed $selected
     * @return string
     */
    function build_checkboxs($name, $list = [], $selected = null)
    {
        $html = [];
        $selected = is_null($selected) ? [] : $selected;
        $selected = is_array($selected) ? $selected : explode(',', $selected);
        foreach ($list as $k => $v) {
            $html[] = sprintf(Form::label("{$name}-{$k}", "%s {$v}"), Form::checkbox($name, $k, in_array($k, $selected), ['id' => "{$name}-{$k}"]));
        }
        return '<div class="checkbox">' . implode(' ', $html) . '</div>';
    }
}


if (!function_exists('build_category_select')) {

    /**
     * 生成分类下拉列表框
     * @param string $name
     * @param string $type
     * @param mixed $selected
     * @param array $attr
     * @return string
     */
    function build_category_select($name, $type, $selected = null, $attr = [], $header = [])
    {
        $tree = Tree::instance();
        $tree->init(Category::getCategoryArray($type), 'pid');
        $categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = $header ? $header : [];
        foreach ($categorylist as $k => $v) {
            $categorydata[$v['id']] = $v['name'];
        }
        $attr = array_merge(['id' => "c-{$name}", 'class' => 'form-control selectpicker'], $attr);
        return build_select($name, $categorydata, $selected, $attr);
    }
}

if (!function_exists('build_toolbar')) {

    /**
     * 生成表格操作按钮栏
     * @param array $btns 按钮组
     * @param array $attr 按钮属性值
     * @return string
     */
    function build_toolbar($id = NULL, $btns = NULL, $attr = [])
    {
        $id = $id ? $id : 'toolbar';
        $auth = \app\admin\library\Auth::instance();
        $controller = str_replace('.', '/', strtolower(think\Request::instance()->controller()));
        $btns = $btns ? $btns : ['refresh', 'add', 'del','export', 'import'];
        if($attr) {
            $btns = array_merge($btns,array_keys($attr));
        }
        $btns = is_array($btns) ? $btns : explode(',', $btns);
        $index = array_search('delete', $btns); //array_search() 函数在数组中搜索某个键值，并返回对应的键名

        if ($index !== FALSE) {
            $btns[$index] = 'del';
        }
        $btnAttr = [
            'refresh' => ['layui-btn layui-btn-primary btn-primary-border layui-btn-sm', 'layui-icon layui-icon-refresh-3', __('Refresh')],
            'add'     => ['layui-btn layui-btn-primary btn-primary-border layui-btn-sm', 'layui-icon layui-icon-add-1', __('Add')],
            'del'     => ['layui-btn layui-btn-primary btn-primary-border-red layui-btn-sm', 'layui-icon layui-icon-delete', __('Delete')],//btn-disabled disabled
            'export'  => ['layui-btn layui-btn-primary btn-primary-border layui-btn-sm', 'layui-icon layui-icon-file', __('Export')],
            'import'  => ['layui-btn layui-btn-primary btn-primary-border layui-btn-sm', 'layui-icon layui-icon-upload', __('Import')],
        ];
        $btnAttr = array_merge($btnAttr, $attr);
//         print_r($btnAttr);
        $html = '';
        $html .= "<script type='text/html' id='{$id}'><div class='layui-btn-container'>";
        foreach ($btns as $k => $v) {
            //如果未定义或没有权限
            if (!isset($btnAttr[$v]) || ($v !== 'refresh' && !$auth->check("{$controller}/{$v}"))) {
                continue;
            }
            list($class, $icon, $text) = $btnAttr[$v];
            $extend = $v == 'import' ? 'id="btn-import-file" data-url="ajax/upload" data-mimetype="csv,xls,xlsx" data-multiple="false"' : '';
            $html .= '<button class="' . $class . '" ' . $extend . ' lay-event ="'.$v.'"><i class="' . $icon . '"></i> ' . $text . '</button>';
        }
        $html .= '</div></script>';
        return $html;
    }
}


if(!function_exists('build_actionbar')) {
    /**
     * 表格操作工具栏
     * @param string $id
     * @param string $btns
     * @param unknown $attr
     * @return string
     */
    function build_actionbar($id = NULL, $btns = NULL, $attr = []) {
        $id = $id ? $id : 'actionbar';
        $auth = \app\admin\library\Auth::instance();
        $controller = str_replace('.', '/', strtolower(think\Request::instance()->controller()));
        $btns = $btns ? $btns : ['view', 'edit', 'del'];
        if($attr) {
            $btns = array_merge($btns,array_keys($attr));
        }
//         print_r($btns);
        $btns = is_array($btns) ? $btns : explode(',', $btns);
        $index = array_search('delete', $btns); //array_search() 函数在数组中搜索某个键值，并返回对应的键名

        if ($index !== FALSE) {
            $btns[$index] = 'del';
        }
        $btnAttr = [
            'view' => ['layui-btn layui-btn-xs layui-btn-normal layui-btn-view', 'layui-icon layui-icon-file-b', __('View'),''],
            'edit'     => ['layui-btn layui-btn-normal layui-btn-xs', 'layui-icon layui-icon-edit', __('Edit'),''],
            'del'     => ['layui-btn layui-btn-danger layui-btn-xs', 'layui-icon layui-icon-delete', __('Delete'),''],//btn-disabled disabled
        ];

        $btnAttr = array_merge($btnAttr, $attr);
        $html = '';
        $html .= "<script type='text/html' id='{$id}'>";
        foreach ($btns as $k => $v) {
            //如果未定义或没有权限
            if (!isset($btnAttr[$v]) || (!$auth->check("{$controller}/{$v}"))) {
                continue;
            }
            list($class, $icon, $text, $url) = $btnAttr[$v];
            if($url) {
                $lay_url = 'lay-url='.$url;
            }else {
                $lay_url = '';
            }
            $html .= '<a class="' . $class . '" lay-event ="'.$v.'" '.$lay_url.'><i class="' . $icon . '"></i> ' . $text . '</a>';
//             $html .= '<a class="' . $class . '" lay-event ="'.$v.'" '.$lay_url.'><i class="' . $icon . '"></i></a>';
        }
        $html .= '</script>';
        return $html;
    }
}

if (!function_exists('build_heading')) {

    /**
     * 生成页面Heading
     *
     * @param string $path 指定的path
     * @return string
     */
    function build_heading($path = NULL, $container = TRUE)
    {
        $title = $content = '';
        if (is_null($path)) {
            $action = request()->action();
            $controller = str_replace('.', '/', request()->controller());
            $path = strtolower($controller . ($action && $action != 'index' ? '/' . $action : ''));
        }
        // 根据当前的URI自动匹配父节点的标题和备注
        $data = Db::name('auth_rule')->where('name', $path)->field('title,remark')->find();

        if ($data) {
            $title = __($data['title']);
            $content = __($data['remark']);
        }
        //         echo $content;
        if (!$content)
            return '';
        $result = '<div class="panel-lead"><em>' . $title . '</em>' . $content . '</div>';
        if ($container) {
            $result = '<div class="panel-heading">' . $result . '</div>';
        }
        return $result;
    }
}


if (!function_exists('get_log_ip_list')){
    /**
     *
     * @return mixed[]|string[]|string[][]|null[][]
     */
    function get_log_ip_list() {
        return json(['0' => __('Operate_status 0'),'1' => __('Operate_status 1'), '2' => '其他']);
    }
}


if(!function_exists('myLog')) {
    /**
     * 打印日志
     * 按时间戳生成文件名
     * @param unknown $str
     */
    function myLog($str) {
        $dir = getcwd(). '/logs/';
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
            //return true;
        } else {
            fclose($fp);
            // return false;
        }
    }
}


if (!function_exists('get_passwd_rule'))
{
    /**
     * 密码验证规则
     * @return mixed
     */
    function get_passwd_rule() {
        $passwd_complexity = Db::name('config')->where(['key' => 'passwd_complexity'])->column('value')[0];
        $password_strategy = Db::name('config_const')->where(['name' => 'password strategy'])->field('value,extend')->find();
        $password_strategy_rule = json_decode($password_strategy['value'], true);
        $password_strategy_tips = json_decode($password_strategy['extend'], true);

        $rule[0] = $password_strategy_rule[$passwd_complexity];
        $rule[1] = '请输入'.$password_strategy_tips[$passwd_complexity];

        return $rule;
    }
}

if (!function_exists('get_config'))
{
    /**
     * 获取数据库配置信息
     * @param unknown $key
     */
    function get_config($key) {
        $config_info = Db::name('config')->where(['key' => $key])->column('value','key');
        return $config_info;
    }
}


if(!function_exists('base64EncodeImage')){
    /**
     * 图片输出
     * @param unknown $image_file
     * @return strig
     */
    function base64EncodeImage ($image_file) {
        $base64_image = '';
        $image_info = @getimagesize($image_file);
        $image_data = @fread(@fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }

}

if(!function_exists('get_sort_array')){
    /**
     *
     * @param unknown $data
     * @param unknown $key
     * @return unknown
     */
    function get_sort_array($data, $key) {
        foreach ($data as $dev_key => $dev_val) {
            $dev_arr[$dev_key] = $dev_val[$key];
        }
        arsort($dev_arr);
        return $dev_arr;
    }
}


if(!function_exists('localDownFile')){
    /**
     * 下载本地的文件
     * @parm url 即本地的文件路径
     */
    function localDownFile($url,$name_ = ''){
        if(is_file($url)){
            if (!$name_){
                $name 	= (strrpos($url, '/') == true )? substr($url , (strrpos($url, '/')+1) ): $url;
            } else {
                $name = $name_;
            }
            if (strpos($_SERVER['HTTP_USER_AGENT'],"Triden") && $name_) {
                $name = urlencode($name);
            }

            $file 	= fopen($url, "r");  //打开文件url
            //         var_dump($file);
            //         echo $url;die();
            header("Content-Type: application/octet-stream"); //指定mime类型为八进制文件流
            //         header("Content-Type: application/zip"); //zip格式的
            header("Accept-Ranges: bytes");
            header("Accept-Length: ".filesize($url));
            header("Content-Disposition: attachment; filename=$name");  //$name是文件的名字，一般在$url的最后
            echo fread($file,filesize($url));
            fclose($file);
        }else{
            return false;
        }
    }
}


if(!function_exists('get_statistics')) {
    function get_statistics($json, &$json_arr) {
        foreach ($json as $k1 => $v1) {
            if(!array_key_exists($k1, $json_arr)) {
                $json_arr[$k1] = $v1;
            } else {
                $json_arr[$k1] += $v1;
            }
        }
    }
}

if(!function_exists('myLog')) {
    function myLog($str) {
        $dir = getcwd(). '/public/logs/';
        echo $dir;
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
            //return true;
        } else {
            fclose($fp);
            // return false;
        }
    }
}


if(!function_exists('get_space_info')) {
    /**
     * 获取磁盘信息
     * @return array
     */
    function get_space_info($space) {
        $info = shell_exec("df -lh  {$space}");
        $info = preg_replace("/\s{1,}/",'_',$info);
        $info = rtrim($info, '_');
        return explode('_', $info);
    }
}


if(!function_exists('space_map')) {
    /**
     * linux 命令执行返回字符串切割
     * @param unknown $command
     * @return array
     */
    function space_map($command) {
        $info = shell_exec("$command");
        $info = preg_replace("/\s{1,}/",'_',$info);
        $info = rtrim($info, '_');
        return explode('_', $info);
    }
}

if(!function_exists('set_config')){
    /**
     * 设置配置文件
     * @param unknown $config_file
     */
    function set_config($config_file,$data) {
        if(file_exists($config_file)){
            $configs = include $config_file;
        }else {
            $configs = [];
        }
        $configs=array_merge($configs,$data);
        $result = file_put_contents($config_file, "<?php\r\nreturn " . var_export($configs, true) . ";");
        return $result;
    }
}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     * @param string $dirname 目录
     * @param bool $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname))
            return false;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }
        return true;
    }

}


if(!function_exists('exportCols')){
    /**
     * 获取导出数据项
     */
    function exportCols() {
        $data = input('post.cols');
        $data = json_decode($data,true)[0];

        foreach ($data as $k => $v) {
            if($v['hide'] === true || $v['type'] == 'checkbox' || isset($v['toolbar'])) {
                unset($data[$k]);
            }
            if ($v['title'] == 'ID') unset($data[$k]);
        }
        sort($data);
        return $data;
    }
}

if(!function_exists('import_excel')) {

    //以excel表的自定义的字段保存数据
    function import_excel($filePath, $SheetIndex = 0) {
        $PHPExcel = new PHPExcel();
        //默认用excel2007读取excel，若格式不对，则用之前的版本进行读取
        $PHPReader = new PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($filePath)) {
            $PHPReader = new PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($filePath)) {
                echo 'no Excel';
                return;
            }
        }

        $PHPExcel = $PHPReader->load($filePath);  //读取Excel文件
        $currentSheet = $PHPExcel->getSheet($SheetIndex);  //读取excel文件中的第一个工作表
        $allColumn = $currentSheet->getHighestColumn(); //取得最大的列号
        $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
        $array_excel = array();  //声明数组
        $allColumn = PHPExcel_Cell::columnIndexFromString($allColumn);

        $array_head = array();
        for ($currentColumn = 0; $currentColumn < $allColumn; $currentColumn++) {
            $val = $currentSheet->getCellByColumnAndRow($currentColumn, 1)->getValue(); //取单元格的值
            if ($val != '') {
                $column = PHPExcel_Cell::stringFromColumnIndex($currentColumn);
                $array_head[$column] = str_replace(" ", "", $val);
            }
        }

        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
            $val = (string) $currentSheet->getCellByColumnAndRow(0, $currentRow)->getValue();
            if ($val === "")
                continue;
                $temp = array();
                for ($currentColumn = 0; $currentColumn < $allColumn; $currentColumn++) {
                    $val = (string) $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    //$val = str_replace("\n", "", trim($val));
                    // $val = preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",$val);#/[\s|　]+/
                    $val = htmlentities(trim($val));
//                     $val = str_replace(" ", "", trim($val));

                    if ($val != '' || $val == 0) {
                        $column = PHPExcel_Cell::stringFromColumnIndex($currentColumn);
                        $temp[$array_head[$column]] = $val;
                    }
                }
                $array_excel[] = $temp;
        }
        return $array_excel;
    }
}










