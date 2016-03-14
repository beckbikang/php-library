<?php
set_time_limit(0);
error_reporting(E_ERROR | E_WARNING);
require_once dirname(dirname(__FILE__)).'/autoload.php';

$db_config = array("host"=>"127.0.0.1",'port'=>'3306',"user"=>"root","password"=>"123456","db"=>"test");
$handle_obj = new MysqlHandle($db_config);

$table_name = "country";
$insert_arr = array("id"=>12,"name"=>"book.sina.com.cn","user_age"=>3);
$sql = SqlBuilder::buildInsertSql($table_name,$insert_arr);


if(isset($argv[1])){
	$filename = "faild".$argv[1].".log";
}else{
	throw new Exception(" input faild, please input argv ");
}
$fp = fopen($filename,"w+");

$i = 100000;
while($i > 0){
	$ret = $handle_obj->query($sql);
	if(!$ret){
		fwrite($fp, $handle_obj->getQueryError()."\n");
	}
	$i--;
	die;
}
fclose($fp);
