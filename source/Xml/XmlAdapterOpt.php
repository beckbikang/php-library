<?php
/*
cusor: i am lazy to write it
user:kang
date:2016年3月24日
project-name:project_name
package_name:package_name

we will use these extension
SimpleXML,DOM,libxml, , SOAP, WDDX, XSL, XML, XMLReader XMLWriter
XMLRPC，

Xmldiff:
	4种比较xml区别的方式：普通的比较，dom比较，内存里面比较，文件比较。
XMLReader:
	读取xml的方式
XMLWriter 
	创建xml的方式
*/
namespace php_library\xml;

class XmlAdapterOptException extends \Exception{}
class XmlAdapterOpt{
	public  $xml_read_obj;
	private $xml = "";
	
	public function __construct($file=""){
		if(!empty($file)){
			if(!is_file($file)){
				throw new XmlAdapterOptException("file open error");
			}
			$this->xml = file_get_contents($file);
			//$this->xml_read_obj = simplexml_load_file($file);
		}
	}
	public function setXml($str){
		$this->xml = $str;
	}
	
	public function useSimpleXml(){
		$this->xml_read_obj = simplexml_load_string($this->xml);
	}
	
	public function useSimpleXmlIterator(){
		$this->xml_read_obj = new \SimpleXmlIterator($this->xml);
	}
	
	function sxiToArray($sxi){
		$a = array();
		for( $sxi->rewind(); $sxi->valid(); $sxi->next() ) {
			if(!array_key_exists($sxi->key(), $a)){
				$a[$sxi->key()] = array();
			}
			if($sxi->hasChildren()){
				$a[$sxi->key()][] = sxiToArray($sxi->current());
			}
			else{
				$a[$sxi->key()][] = strval($sxi->current());
			}
		}
		return $a;
	}
	
	public function convertXmlBySimpleXmlIterator(){
		return $this->convertDoIt($this->xml_read_obj);
	}
	//copy from php.net
	private function convertDoIt($iterator_obj){
		$xml_arr = array();
		for($iterator_obj->rewind();$iterator_obj->valid();$iterator_obj->next()){
			$key = $iterator_obj->key();
			if(!isset($xml_arr[$key])) $xml_arr[$key] = array();
			if(!$iterator_obj->hasChildren()){
				$xml_arr[$key] = strval($iterator_obj->current());
			}else{
				$xml_arr[$key][] = $this->convertDoIt($iterator_obj->current());
			}
		}
		return $xml_arr;
	}
	
	public function getDataBySimpleXpath($xpath){
		$this->useSimpleXml();
		$ret = $this->xml_read_obj->xpath($xpath);

		if(!empty($ret)){
			try{
				$ret = strval($ret[0]);
			}catch (Exception $e){
				echo "convert faild";
			}
		}
		return $ret;
	}
	
	public function readDateBySimpleXml(){
		$this->useSimpleXml();
	}
	//deal big data
	public function dealBigData($max="4096M"){
		set_time_limit(0);
		ini_set('memory_limit','20480M');
	}
	
	//see more in http://www.w3school.com.cn/htmldom/
	public function createDomObj($file=""){
		$this->xml_read_obj = new \DOMDocument();
		if(empty($file)){
			$this->xml_read_obj->loadXML($this->xml);
		}
	}
	public function getTageValueList($tag){
		$list = array();
		$ret = $this->xml_read_obj->getElementsByTagName("name");
		if(!empty($ret)){
			foreach($ret as $v){
				$list[] = $v->nodeValue;
			}
		}
		return $list;
	}
	
	
	public function __destruct() {
		$this->xml_read_obj = "";
		$this->xml = "";
	}	
}

$xml = '
<xml>
<list>
<name>tom</name>
<age>21</age>
</list>
<list>
<name>tim</name>
<age>31</age>
</list>
</xml>';


$obj = new XmlAdapterOpt();
$obj->setXml($xml);
$xpath ="/xml/list[2]/name";
$ret = $obj->getDataBySimpleXpath($xpath);
$obj->useSimpleXmlIterator();
$arr = $obj->convertXmlBySimpleXmlIterator();
print_r($arr);
$obj->createDomObj();
var_dump($obj->getTageValueList("name"));





