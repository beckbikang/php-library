<?php
namespace php_library\dir;
class DirOpt{
	
	//dir path
	public static $list_path = array();
	//get dir list
	public static function getDirList($path){
		if(!is_dir($path)) return false;
		$dir = Dir($path);
		while (false !== ($entry = $dir->read())) {
			if($entry == "." || $entry == "..") continue;
			$entry_list = $path."/".$entry;
			self::$list_path[] = $entry_list;
			if(is_file($entry_list)){
				echo $entry_list."\n";
			}else{
				call_user_func(__NAMESPACE__."\DirOpt::getDirList",$entry_list);
			}
		}
		$dir->close();
		return self::$list_path;
	}

}
/*
$path = "/Users/kang/Documents/phpProject/otherproject/php-library/source";
$obj = new DirOpt();
print_r($obj->getDirList($path));
*/
