<?php
/*
cusor:
user:kang
date:2016年3月21日
project-name:project_name
package_name:package_name
*/
namespace php_library\cache;
interface InterfaceCache{
	public function getCache($key);
	public function setCache($key,$data,$exp_time);
	public function clearCache($key);
	public function clearAll();
}