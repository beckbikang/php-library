<?php
/*
cusor:模拟http请求
user:kang
date:2016年3月22日
project-name:project_name
package_name:package_name
*/
namespace php_library\curl;

class CurlOptException extends \Exception{}

/**
 * to check https data 
 * http://www.cnblogs.com/ainiaa/archive/2011/11/08/2241385.html
 * 
* @ClassName: php_library\curl$CurlOpt 
* @Description: 
* @author:bikang@book.sina.com
* @date 2016年3月23日 下午2:14:47 
*
 */
class CurlOpt{
	const DEFAULT_FUNC = "get";
	private $ch;
	private $url;
	private $has_head = 0;
	private $return_trasfer = 1;
	private $has_ssl = 0;
	private $post_fields = array();
	private $curl_type;
	private $error;
	private $errno;
	private $cookie_dir = "./temp/";
	private $timeout = 3;
	private $allow_redict = 1;
	private $max_redict = 20;
	private $post_data = array();
	private $cerfy_ca="";
	
	
	
	public static $m_user_agents = array(
			'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT)', // 'IE5'
			'Mozilla/4.0 (compatible; MSIE 5.0; Windows 98; DigExt)', // 'IE5'
			'Mozilla/4.0 (compatible; MSIE 6.0; Windows XP 5.1)', // 'IE6'
			'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', // 'IE7'
			'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.4) Gecko/20050511 Firefix/1.0.4', // 'FireFox1'
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.6) Gecko/20040206 Firefox/0.8', // 'FireFox0.8'
			'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0', // 'FireFox3'
			'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', // 'GoogleBot'
			'Mozilla/4.8 [en] (Windows NT 6.0; U)', // 'Netscape'
			'Opera/9.25 (Windows NT 6.0; U; en)', // 'Opera'
	);
	
	public function  setParams($params){
		foreach ($params as $k=>$v){
			$this->$k = $v;
		}
	}
	
	public function __construct($url=""){
		if(!extension_loaded("curl")){
			throw new CurlOptException("not install curl");
		}
		if(!empty($url)){
			$this->url = $url;
		}
		$this->ch = curl_init();
	}
	public function setUrl($url){
		$this->url = $url;
	}
	public function setPostData($data){
		$this->post_data = $data;
	}
	
	public function setCaFile($file){
		$this->cerfy_ca =$file;
	}
	
	public function checkUrl(){
		if(empty($this->url)){
			throw new CurlOptException("not input url");
		}
		if(stripos($this->url,"https:") !== false){
			//检查证书来源
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);
			//从证书中检查SSL加密算法是否存在
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2);
			if(empty($this->cerfy_ca)){
				throw new CurlOptException("not CA file");
			}
			curl_setopt($this->ch, CURLOPT_CAINFO, $this->cerfy_ca);
		}
		curl_setopt($this->ch, CURLOPT_URL, $this->url);
		if(!empty($this->cookie_dir) && !is_dir($this->cookie_dir)){
			$mk_ret = mkdir($this->cookie_dir,0777,true);
			if(!$mk_ret){
				throw new CurlOptException("make cookie faild");
			}
		}
		$cookie_file = $this->getCookieFile();
		//生成cookie_file
		file_put_contents($cookie_file, "");
		//存cookie
		curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie_file);
		//访问页面时取cookie
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($this->ch,CURLOPT_USERAGENT,array_rand(self::$m_user_agents,1));
		curl_setopt($this->ch, CURLOPT_HEADER, $this->has_head);
		curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,$this->return_trasfer);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
		//页面发生跳转的设置
		//CURLOPT_FOLLOWLOCATION即表示自动进行跳转抓取，CURLOPT_MAXREDIRS表示最多允许跳转多少次。
		curl_setopt($this->ch, CURLOPT_MAXREDIRS,$this->max_redict);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION,$this->allow_redict);
    
	}
	
	public function getCookieFile(){
		return $this->cookie_dir.md5($this->url);
	}
	
	public function getError(){
		return $this->error;
	}
	public function getErrorno(){
		return $this->errno;
	}
	
	public function runIt($type=self::DEFAULT_FUNC,$data = array()){
		$this->checkUrl();
		$this->$type();
		if(!empty($data)){
			$this->post_data = $data;
		}
		$ret = curl_exec($this->ch);
		$this->error =  curl_error($this->ch);
		$this->errno =  curl_errno($this->ch);
		if($this->errno > 0){
			throw new CurlOptException("errno ".$this->errno." error info ".$this->error);
		}else{
			return $ret;
		}
	}
	
	public function get(){
		
	}
	public function post(){
		curl_setopt($this->ch, CURLOPT_POST, 1);
		curl_setopt($this->ch,CURLOPT_POSTFIELDS,$this->post_data);
	}
	
	public function put(){
		if(is_array($this->post_data)){
			$this->post_data = http_build_query($this->post_data);
		}
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($this->post_data)));
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->post_data);
	}
	
	public function delete(){
		if(is_array($this->post_data)){
			$this->post_data = http_build_query($this->post_data);
		}
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DEL');
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($this->post_data)));
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->post_data);
	}
	public function __destruct(){
		curl_close($this->ch);
	}
}
function testHttpsDouban(){
	$url = "https://www.douban.com/";
	$obj = new CurlOpt($url);
	$file = getcwd()."/douban";
	$obj->setCaFile($file);
	$ret = $obj->runIt();
	echo $ret;
}
function testHttpsBaidu(){
	$url = "https://www.baidu.com/";
	$obj = new CurlOpt($url);
	echo $file = getcwd()."/BAIDU_CA.crt";
	$obj->setCaFile($file);
	$ret = $obj->runIt();
	echo $ret;
}
testHttpsDouban();
//testHttpsBaidu();

