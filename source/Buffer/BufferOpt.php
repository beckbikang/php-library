<?php
/*
cusor: control buffer 
		php control buffer   echo/print -> php buffer -> tcp buffer -> browser
user:kang
date:2016年3月25日
project-name:project_name
package_name:package_name
*/
namespace php_library\buffer;
class BufferOpt{
	
	public function startBuffer(){
		ob_start();
	}
	
	public function endFlushBuffer(){
		ob_end_flush();
	}
	
	public function getFlushBuffer(){
		ob_end_clean();
	}
	
	public function getBufferLen(){
		return ob_get_length();
	}
	
	public function getBufferData(){
		return ob_get_contents();
	}
	
	public function getBufferLevel(){
		return ob_get_level();
	}
	
	public function flushData(){
		flush();
	}
	
	public function offBuffer(){
		ini_set('output_buffering', 0);
	}
	
	public function showBufferConfig(){
		print ini_get('output_buffering');
	}
}

$obj = new BufferOpt();
$obj->showBufferConfig();

$obj->startBuffer();
for($i=1;$i<100000;$i++){
	echo "adfslfsdlfjsfklklfkldfsklkljklfdfjklsdjkldsjklfsjklfsjklfsjklfjkdfjkljklfjklfjkldjkldkljkljgljkllal;";
	usleep(10);
}
$obj->endFlushBuffer();


