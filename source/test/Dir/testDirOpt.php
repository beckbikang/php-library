<?php
set_time_limit(0);
error_reporting(E_ERROR | E_WARNING);
require_once dirname(dirname(__FILE__)).'/autoload.php';
require_once dirname(dirname(dirname(__FILE__))).'/Dir/DirOpt.php';
use php_library\dir;

$path = "/Users/kang/Documents/phpProject/otherproject/php-library/source";
$obj = new php_library\dir\DirOpt();
print_r($obj->getDirList($path));
