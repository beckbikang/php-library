<?php
/*
cusor:
user:kang
date:2016年3月21日
project-name:project_name
package_name:package_name
*/
namespace php_library\cache;
require_once "InterfaceCache.php";

class MemcacheCacheException extends \Exception{}
class MemcacheCache implements InterfaceCache{
	private $memcache;
	public function addServer($config){
		$this->memcache = new \Memcache;
		if(empty($config)){
			throw new MemcacheException("need Memcache Config");
		}
		foreach($config as $v){
			$this->memcache->addServer($v["host"],$v["port"]);
		}
	}

	public function decrement($key,$number){
		return $this->memcache->decrement($key, $number);
	}
	
	public function replace($key,$value,$exp_time){
		return $this->memcache->replace($key, $value, false, $exp_time);
	}
	public function increment($key,$number){
		return $this->memcache->increment($key, $number);
	}
	
	public function close() {
		return $this->memcache->close();
	}
	
	public function getCache($key){
		return $this->memcache->get($key);	
	}
	
	public function setCache($key,$data,$exp_time){
		return $this->memcache->set($key, $data,0, $exp_time);
	}
	
	public function setCompreseedCache($key,$data,$flag=MEMCACHE_COMPRESSED,$exp_time){
		return $this->memcache->set($key, $data,$flag, $exp_time);
	}

	public function clearCache($key){
		return $this->memcache->delete($key,0);
	}
	
	public function clearCacheDelay($key,$delay){
		return $this->memcache->delete($key, $delay);
	}
	public function clearAll(){
		return $this->memcache->flush();
	}
	
	public function __destruct() {
		$this->close();
	}
}