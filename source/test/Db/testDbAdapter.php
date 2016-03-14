<?php
/*
cusor:
user:kang
date:2016年1月18日
project-name:project_name
package_name:package_name
*/


require_once dirname(dirname(__FILE__)).'/autoload.php';


$db_config = array("host"=>"127.0.0.1",'port'=>'3306',"user"=>"root","password"=>"123456","db"=>"test");
$db_obj = DbAdapter::CreateDb($db_config);

$sql = "select * from country limit 1";

var_dump($db_obj->fetchRow($sql));


