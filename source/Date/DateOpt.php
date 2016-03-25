<?php
/*
cusor:
user:kang
date:2016年3月16日
project-name:project_name
package_name:package_name
*/
namespace php_library\date;
class DateOpt{
	private $timestmp,$year,$month,$day;
	
	public function __construct(){
		$this->timestmp = time();
		$this->year = date("Y");
		$this->month = date("m");
		$this->day = date("d");
	}
	//next monday
	public  function getNextMonday($time=""){
		if(!$time){
			$time = $this->timestmp;
		}
		return date("Y-m-d",$time-(date("N", $time)-8)*86400);
	}
	//pre monday
	public  function getPreMonday($time=""){
		if(!$time){
			$time = $this->timestmp;
		}
		return date("Y-m-d",$time-(date("N", $time)+6)*86400);
	}
	//next month first day
	public function getNextMonthFirstDay($time=""){
		if(empty($time)){
			$year = $this->year;
			$month = $this->month;
		}else{
			$year = date("Y",$time);
			$month = date("m",$time);
		}
		if($month == 12){
			$month = 1;
		}else{
			++$month;
		}
		return $year."-".$month."-01";
	}
}













