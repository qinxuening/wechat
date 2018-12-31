<?php
/**
 * @author: qxn
 * @E-mial: 2423859713@qq.com
 * @date: 2018年11月24日 下午5:51:19
 */
namespace app\common\library\traits;
use think\Config;

trait Upload{
    /**
     * 上传文件
     */
    public function upload()
    {
        try {
            Config::set('default_return_type', 'json');
            $file = request()->file('file');
            if (empty($file)) {
                return json(['code' => -2, 'status' => 'error', 'msg' => __('No file upload or server upload limit exceeded')]);
            }
            //判断是否已经存在附件
            $sha1 = $file->hash();
    
            $upload = $this->setConfig();//Config::get('upload')
//             print_r($upload);die();
            preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
    
            $type = strtolower($matches[2]);
            $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
            $size = (int)$upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
            $fileInfo = $file->getInfo(); //获取上传文件的信息,[name]文件名、[type]文件类型、[tmp_name]生成临时文件路径，[size]大小
    
            $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION)); //获取文件后缀
            $suffix = $suffix ? $suffix : 'file';
    
            $mimetypeArr = explode(',', $upload['mimetype']);//文件类型
            $typeArr = explode('/', $fileInfo['type']);
            //验证文件后缀
            if ($upload['mimetype'] !== '*' && !in_array($suffix, $mimetypeArr) && !in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr)) {
                return json(['code' => -3, 'status' => 'error', 'msg' => __('Uploaded file format is limited')]);
            }
            
            //strripos() 函数查找字符串在另一字符串中最后一次出现的位置。
            $replaceArr = [
                '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
                '{suffix}'   => $suffix,
                '{.suffix}'  => $suffix ? '.' . $suffix : '',
                '{date}'     => date('Ym', time()),
                '{filemd5}'  => md5(microtime()),//md5_file($fileInfo['tmp_name']),
            ];
    
            $savekey = $upload['uploadfile'];
            $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);
            //str_replace(find,replace,string,count)
            //str_replace() 函数以其他字符替换字符串中的一些字符（区分大小写）
            
            
            $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1); //strripos() 函数查找字符串在另一字符串中最后一次出现的位置。
    
//             $fileName= md5_file($fileInfo['tmp_name']); //文件名不改变
            $fileName = substr($savekey, strripos($savekey, '/') + 1);
    
            $splInfo = $file->validate(['size' => $size])->move(ROOT_PATH . '/public' . $uploadDir, $fileName);
    
            if ($splInfo) {
                $params = array(
                    'filesize'    => $fileInfo['size'],
                    'imagetype'   => $suffix,
                    'imageframes' => 0,
                    'mimetype'    => $fileInfo['type'],
                    'url'         => $uploadDir . $splInfo->getSaveName(),
                    'uploadtime'  => time(),
                    'storage'     => 'local',
                    'sha1'        => $sha1,
                );
    
                return json(['code' => 1, 'status' => 'success', 'msg' => '操作成功', 'data' => $params]);
            } else {
                return json(['code' => -1, 'status' => 'error', 'msg' => $file->getError()]);
            }
    
        } catch (\Exception $e) {
            return json(['code' => -4, 'status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    
    public function setConfig() {
        
    }
    
}