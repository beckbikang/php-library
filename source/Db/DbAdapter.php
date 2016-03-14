<?php


abstract class DbAdapter{
	public static $class_suff = "Handle";
	public abstract function query($sql);
	public abstract function insert($sql);
	public abstract function fetchRow($sql);
	public abstract function fetchRows($sql);
	public abstract function getQueryError();
	public function startTrans(){}
	public function transCommit(){}
	public function transRollback(){}
	public static function CreateDb($config,$type="Mysql"){
		$class_name = ucwords($type).self::$class_suff;
		return new $class_name($config);
	}
}