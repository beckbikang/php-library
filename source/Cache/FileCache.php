<?php
/*
cusor:
user:kang
date:2016年3月20日
project-name:project_name
package_name:package_name
*/
namespace php_library\cache;

class FileCacheException extends \Exception{}
class FileCache{
	
	private $file_path;
	const EXPLODE = "#@#";

	public function __construct($file_path=""){
		if(empty($file_path)){
			$this->file_path = dirname(__FILE__)."/tmp_filecache/";
			if(!is_dir($this->file_path)){
				$ret = mkdir($this->file_path);
				if(!$ret){
					throw new FileCacheException("create store path faild");
				}
			}
		}
	}
	
	public function setCache($key,$data,$exp_time=1800){
		$data = date("Y-m-d H:i:s").self::EXPLODE.$exp_time.self::EXPLODE.serialize($data);
		return file_put_contents($this->file_path.md5($key), $data);
	}

	public function getCache($key){
		$ret = false;
		$content = @file_get_contents($this->file_path.md5($key));
		if(empty($content)){
			throw new FileCacheException("fetch contents faild");
		}
		$arr = explode(self::EXPLODE,$content);
		if(empty($arr) || !is_array($arr) || count($arr) != 3){
			throw new FileCacheException("fetch contents faild");
		}
		//check cache time
		if(time() < (strtotime($arr[0])+intval($arr[1]))){
			$ret = unserialize($arr[2]);
		}
		return $ret;
	}
}

$obj_file_cache = new FileCache();

$obj_file_cache->setCache("hi","world",3);
var_dump($obj_file_cache->getCache("hi"));
sleep(4);
var_dump($obj_file_cache->getCache("hi"));










