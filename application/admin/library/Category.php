<?php
namespace app\admin\library;
class  Category{
	/**
	 * 组合一维数组
	 * @param unknown $cate
	 * @param string $html
	 * @param number $pid
	 * @param number $level
	 * @return Ambigous <multitype:, multitype:string >
	 */
	Static public function unlimitedForLevel($cate,$html = '--', $pid = 0,$level = 0 , $parentid = 'pid' ){
		//静态方法和非静态方法之间有一个很重要的区别，就是在调用静态方法时，我们不需要创建类的实例。
		$arr = array();
		foreach ($cate as $v) {
			if ($v[$parentid] == $pid) {
				$v['level'] = $level + 1;
				$v['html'] = str_repeat($html, $level);// 函数把字符串重复指定的次数。
				if($level){
					$v['space'] = '└─';
				}
				$arr[] = $v;
				$arr = array_merge($arr, self::unlimitedForLevel($cate, $html, $v['id'], $level + 1));//$arr 就是一个数组，$arr[]就是一个容器
			}
		}
		return $arr;
	}
	
	/**
	 * 组合多维数组
	 * 递归函数即为自调用函数，在函数体内直接或间接自己调用自己，但需要设置自调用的条件，若满足条件，则调用函数本身，若不满足则终止本函数的自调用，然后把目前流程的主控权交回给上一层函数来执行
	 * 必须考虑两点：
		1） 能否把问题转化成递归形式的描述；
		2） 是否有递归结束的边界条件。
	 *@author qxn
	 * @param unknown $cate
	 * @param number $pid
	 * @return multitype:
	 */
	static public function unlimiteForLayer ($cate, $name = 'child', $pid = 0 , $parentid = 'pid') {
		$arr = array();
		foreach ($cate as $v) {
			//echo $v['parentid'];
			if($v[$parentid] == $pid) {
				//寻找所有$v['parentid'] == 每个$pid的情况
				//每个pid都循环一遍
				if(self::unlimiteForLayer($cate, $name, $v['id'])) {
				    $v[$name] = self::unlimiteForLayer($cate, $name, $v['id']);
				}
				$arr[] = $v;
			}
		}
		return  $arr;//
	}
	
	/**
	 * 传递一个子分类ID 返回所有的父级分类
	 * @param unknown $cate
	 * @param unknown $id
	 * @return Ambigous <multitype:, multitype:unknown >
	 */
	static public function getParent($cate, $id) {
		$arr = array();
		foreach ($cate as $v) {
			if ($v['id'] == $id) {
				$arr[] = $v;
				$arr = array_merge(self::getParent($cate, $v['pid']), $arr);
			}
		}
		return $arr;
	}
	
	/**
	 * @author qxn
	 * 传递一个父级分类ID返回所有字子类ID
	 * @param unknown $cate
	 * @param unknown $pid
	 * @return Ambigous <multitype:, multitype:unknown >
	 */
	static public function getChildsId ($cate, $pid) {
		$arr = array();
		foreach ($cate as $v) {
			if ($v['pid'] == $pid) {
				$arr[] = $v['id'];
				$arr = array_merge($arr, self::getChildsId($cate, $v['id']));
			}
		}
		return $arr;
	}
	
	/**
	 * @author
	 * 传递一个父级分类ID返回所有子分类
	 * @param unknown $cate
	 * @param unknown $pid
	 * @return Ambigous <multitype:, multitype:unknown >
	 */
	static public function getChilds($cate, $pid) {
		$arr = array();
		foreach ($cate as $v) {
			if ($v['parentid'] == $pid) {
				$arr[] = $v;
				$arr = array_merge($arr, self::getChildsId($cate, $v['id']));
			}
		}
		return $arr;
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}