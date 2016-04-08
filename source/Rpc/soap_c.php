<?php
/*
cusor:
user:kang
date:2016年4月8日
project-name:project_name
package_name:package_name
*/

try{
	$client = new SoapClient(null,
		array(
			'location' => "http://221.179.190.191/prog/search/searchfront/soap_s.php",
				'uri'  => "http://221.179.190.191/prog/search/searchfront/soap_s.php",
			'trace'    => 1
		)
		);
	 echo $return = $client->__soapCall("say", array("world"));
	 
}catch(SoapFault $f){
	print_r($f->faultstring);
}