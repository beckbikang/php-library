<?php

require_once dirname(__FILE__)."/log_conf.php";

interface logInterface{
	public function log($params);
}



class LoggerAdapter {
	public $log_config = null;
	public $log_obj = null;
	public static function log($params,$type="File"){
		$classname = $type."Logger";
		$log_obj = new $classname;
		$log_obj->log($params);
	}
	public static function getFileLogConfig(){
		global $log_config;
		return $log_config;
	}
}

class FileLogger implements logInterface{
	
	protected $_log_path;
	protected $_threshold	= 3;
	protected $_date_fmt	= 'Y-m-d H:i:s';
	protected $_enabled	= TRUE;
	protected $_levels	= array('ERROR' => '1', 'LOG' => '2', 'SLOW_PHP' => '3','DEBUG' => '4',  'INFO' => '5', 'ALL' => '6');
	protected $_msg_sep = ":";
	
	
	public function log($params){
		$log_config = LoggerAdapter::getFileLogConfig();
		$log_dir = $log_config["log_file"];
		if(!is_dir($log_dir)){
			$ret = mkdir($log_dir,0777,true);
			if(!$ret){
				return false;
			}
		}
		if(!isset($params["filename"])){
			$log_file = $log_dir."/".date("Ymd").".log";
		}else{
			if(isset($params["filename_daily"])){
				$params["filename"] = date("Ymd")."-".$params["filename"];
			}
			$log_file = $log_dir."/".$params["filename"].".log";
		}
		
		$fp = @fopen($log_file,"a+");
		if(isset($params["lock"]) && $params["lock"] == 1){
			flock($fp, LOCK_EX | LOCK_NB);
		}
		@fwrite($fp,date($this->_date_fmt).$this->_msg_sep.implode($this->_msg_sep,$params)."\n");
		@fflush($fp);
		@fclose($fp);
	}
}

class QueueLogger implements logInterface{
	public function log($params){
		
	}
	
}
class DbLogger implements logInterface{
	public function log($params){
	
	}
}









