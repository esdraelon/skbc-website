<?php

error_reporting(E_ERROR | E_PARSE);
session_start();

require_once(".env");

include('lib/Yapo/class.Yapo.php');

class Lib {
	static $Lib;
	
	static function init() {
		Lib::$Lib = new ClassLoader();
		$db = new YapoMysql(__DBSERVER__, __DBDBNAME__, __DBUSERNM__, __DBPASSWD__);
		Lib::$Lib->Database = $db;
	}
}

class ClassLoader {
	var $Database;

	function __construct() {

	}
	
	function __get($class) {
		return ClassLoader::_load_class($class);
	}
	
	static function _load_class($class) {
		include_once('lib/class.' . $class . '.php');
		if (class_exists($class)) {
			$c = new $class();
			Lib::$Lib->$class = $c;
		}
		return $c;
	}
}

Lib::init();

spl_autoload_register(array('ClassLoader', '_load_class'));

foreach (glob(__DIR__ . "/functions/*.php") as $filename)
{
  include $filename;
}
?>