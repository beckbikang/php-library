<?php

class SqlBuilder{
	public static  function clearValues($values){
		if(empty($values) || !is_array($values)) return '';
		foreach($values as &$v){
			if(is_int($v)){
				$v = intval($v);
			}else{
				$v = "'".mysql_escape_string($v)."'";
			}
		}
		return $values;
	}

	public static function buildInsertSql($tableName,$keyValueArr){
		if(empty($tableName) || empty($keyValueArr)) return '';
		$columns = array_keys($keyValueArr);
		$values  = array_values($keyValueArr);
		$values = self::clearValues($values);
		$sql = "insert into `{$tableName}`(";
		$sql .= implode(",",$columns).")values(";
		$sql .= implode(",",$values).")";
		return $sql;
	}


}
