<?php
/*
cusor:
user:kang
date:2016年4月7日
project-name:project_name
package_name:package_name
*/

/**
 * 函数：提供给客户端进行连接XML-RPC服务器端的函数
 * 参数：
 * $host 需要连接的主机
 * $port 连接主机的端口
 * $rpc_server XML-RPC服务器端文件
 * $request 封装的XML请求信息
 * 返回：连接成功成功返回由服务器端返回的XML信息，失败返回false
 */
function rpc_client_call($host, $port, $rpc_server, $request) {

	//打开指定的服务器端
	$fp = fsockopen($host, $port);

	//构造需要进行通信的XML-RPC服务器端的查询POST请求信息
	$query = "POST $rpc_server HTTP/1.0\nUser_Agent: XML-RPC Client\nHost: ".$host."\nContent-Type: text/xml\nContent-Length: ".strlen($request)."\n\n".$request."\n";

	//把构造好的HTTP协议发送给服务器，失败返回false
	if (!fputs($fp, $query, strlen($query)))
	{
		$errstr = "Write error";
		return false;
	}
	//获取从服务器端返回的所有信息，包括HTTP头和XML信息
	$contents = "";
	while (!feof($fp))
	{
		$contents .= fgets($fp);
	}
	//关闭连接资源后返回获取的内容
	fclose($fp);
	return $contents;
}

//构造连接RPC服务器端的信息
$host = "221.179.190.191";
$port = 80;
$rpc_server = "/prog/search/searchfront/server.php";

$request = xmlrpc_encode_request("rpc_server", "get");
$response = rpc_client_call($host, $port, $rpc_server, $request);
$split = "\r\n";
$xml = explode($split, $response);
$xml = array_pop($xml);
$response = xmlrpc_decode($xml);
//输出从RPC服务器端获取的信息
print_r($response);