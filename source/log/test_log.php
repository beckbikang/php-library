<?php
require_once dirname(__FILE__)."/Logger.php";

$flag = false;

if( isset($argv[1])){
	$flag = true;
}

$log_arr = array(
		"classify"=> "book_chapter",
		"level"=> "book_info",
		"msg"=> "chapter is null",
		"filename" => "test",
		"lock" => 0,
		);

if($flag) {
	$log_arr["msg"] = "book is null";
}
$i = 0;
while($i < 1000){
	LoggerAdapter::log($log_arr);
	$i++;
}


