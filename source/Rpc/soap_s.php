<?php
/*
cusor:
user:kang
date:2016年4月8日
project-name:project_name
package_name:package_name
*/
class ServerTest{
	
	public function say(){
		return "hello".print_r(func_get_args(),true);
	}
}

try{
	$server = new SoapServer(null,array(
		'uri' => 'http://221.179.190.191/prog/search/searchfront/soap_s.php'	
	));
	$server->setClass("ServerTest");
	$server->handle();
}catch(SoapFault $f){
	print_r($f->faultstring);
}
