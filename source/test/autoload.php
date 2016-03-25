<?php
/*
cusor:
user:kang
date:2016年1月18日
project-name:project_name
package_name:package_name
*/

define("BATH_DIR",dirname(dirname(__FILE__))."/");

$load_dir = array(
		BATH_DIR."Db/",
		BATH_DIR."Db/mysql/",
		BATH_DIR."Db/dpool/",
		BATH_DIR."Dir/",
);


function load($classname){
	global $load_dir;
	foreach($load_dir as $dir){
		$file = $dir.$classname.".php";
		if(is_file($file)){
			include_once $file;
		}
	}
}

spl_autoload_register("load");