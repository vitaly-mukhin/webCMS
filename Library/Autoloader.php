<?php

/**
 * Description of Autoloader
 *
 * @author Mukhenok
 */
class Autoloader {

	protected static $_stack = array();

	protected function __construct() {
		spl_autoload_register(function($class) {
					Autoloader::i()->__invoke($class);
				});
	}

	/**
	 *
	 * @return Autoloader
	 */
	public static function i() {
		static $instance = null;
		if(!$instance) {
			$instance = new static();
		}
		return $instance;
	}

	/**
	 *
	 * @param type $pref
	 * @param type $path
	 * @param type $sub
	 * @return Autoloader 
	 */
	public function pushAutoload($pref, $path, $sub = null) {
		self::$_stack[$pref] = array($path, $sub);
		return $this;
	}

	/**
	 * Block_Example => block/block.Example.php
	 * Block_Example_New => block/example/example.New.php
	 * BlockExample => class/class.Example.php
	 *
	 * @param type $class
	 * @return type 
	 */
	public function __invoke($class) {
		$class_array = explode('_', $class);
		
		if(count($class_array)>1&&isset(self::$_stack[$class_array[0]])) {
			$filename = $this->_invoke($class_array[0], array_slice($class_array, 1));
		} else {
			if(empty(self::$_stack[''])) {
				throw new Exception('Default class path isn\'t added');
			}
			$filename = $this->_invoke('', $class_array);
		}

		if(file_exists($filename)) {
			require_once $filename;
			return true;
		} else {
			return false;
		}
	}

	protected function _invoke($pref, $array) {
		//	array(example, New)
		$cls = (count($array)>1) ? array(strtolower($array[count($array)-2]), $array[count($array)-1]) : array($this->_getSubprefix($pref), $array[0]);
		$path = (count($array)>1) ? array_slice($array, 0, count($array)-1) : array();

		return $this->_getPathPrefix($pref).($path ? implode('/', $path).'/' : '').($cls[0] ? strtolower($cls[0]).'.' : '').$cls[1].'.php';
	}

	protected function _getSubprefix($pref) {
		$a = self::$_stack[$pref];

		switch (true) {
			case is_null($a[1]):
				return 'class';

			case $a[1]==false:
				return '';

			default:
				return $a[1];
		}
	}

	protected function _getPathPrefix($pref) {
		return self::$_stack[$pref][0];
	}

}