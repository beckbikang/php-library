<?php
/*
cusor:
user:kang
date:2016年4月7日
project-name:project_name
package_name:package_name
*/

/**
 * 
 * php常用的rpc服务：
 * 
 * 	XMLRPC,SOAP,Yar。使用他们。
 * 	目前进行Web Service通信有两种协议标准，
 * 一种是XML-RPC，另外一种是SOAP。
 * XML-RPC比较简单，出现时间比较早，SOAP比较复杂，主要是一些需要稳定、健壮、安全并且复杂交互的时候使用。
 * 
 * 	XMLRPC 基于xml的远程调用
 * 
 * 在soap编写web service的过程中主要用到了SoapClient,SoapServer,SoapFault三个类。
 * 
 * 
 * 
 * 
 * 
 * 
 * 
* @ClassName: type_name 
* @Description: 
* @author:bikang@book.sina.com
* @date 2016年4月7日 上午11:20:25 
* 
* tags
 */


class RpcOptException extends Exception{}
//bad
class RpcOpt{
	public $time_out = 3;
	private $socket_errno="";
	private $socket_errstr="";
	private $split = "\r\n";

	
	public function __construct(){
		if(!extension_loaded("xmlrpc")){
			throw new RpcOptException(" rpc opt exception");
		}
	}
	
	public function getSocketErrno(){
		return $this->socket_errno;
	}
	public function getSocketErrstr(){
		return $this->socket_errstr;	
	}
	
	public function xmlRpcRequestSocket($host,$port,$rpc_server,$request,$time_out=""){
		if(!empty($time_out)) $this->time_out = $time_out;
		$fp = @fsockopen($host,$port,$this->socket_errno,$this->socket_errstr,$this->time_out);
		if(!$fp) throw new RpcOptException("open socket faild");
		$query = "POST $rpc_server HTTP/1.0\n";
		$query .= "User_Agent: XML-RPC Client\n";
		$query .= "Host:$host\n";
		$query .= "Content-Type: text/xml\n";
		$query .= "Content-Length: ".strlen($request)."\n";
		$query .= "\n$request\n";
		fwrite($fp, $query);
		$contents = "";
		while (!feof($fp)) {
			$contents .= fgets($fp, 4096);
		}
		fclose($fp);
		return $contents;
	}
	
	//xmlrpc_encode_request
	public function xmlRpcRequest($host,$port,$rpc_sever,$method,$params){
		$request = xmlrpc_encode_request($method, $params);
		$response = $this->xmlRpcRequestSocket($host,$port,$rpc_server,$request);
		$xml_arr = explode($this->split, $response);
		if(!is_array($xml_arr) || empty($xml_arr)) return false;
		$xml = array_pop($xml_arr);
		return xmlrpc_decode($xml);
	}
	
	//xmlrpc_server
	public function xmlRpcServer($method_arr,$out_arr=NULL){
		$xml_server = xmlrpc_server_create();
		foreach($method_arr as $v){
			$method = $v[0];
			$real_method = $v[1];
			xmlrpc_server_register_method($xml_server,$method,$real_method);
		}
		$xmlrpc_response = xmlrpc_server_call_method($xml_server, $HTTP_RAW_POST_DATA, null);
		header("Content-Type: text/xml");
		echo $xmlrpc_response;
		xmlrpc_server_destroy($xml_server);
	}
}

$host = "221.179.190.191";
$port = 80;
$rpc_server = "/prog/search/searchfront/server.php";





