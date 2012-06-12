<?php

/**
 * Description of Autoloader
 * 
 * @author Vitaliy_Mukhin
 * 
 */

require __DIR__ . DIRECTORY_SEPARATOR . 'ILoader.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'Loader.php';

class Autoloader {

	/**
	 * Registered loaders
	 *
	 * @var array Loaders
	 */
	protected static $loaders;

	public static function load($class) {
		$paths = array();
		foreach (self::$loaders as $Loader) {
			/* @var $Loader Loader */
			$file = $Loader->getFile($class);
			var_dump($file);
			if (!$file) {
				continue;
			}
			if (file_exists($file)) {
				require_once $file;
				if (class_exists($class, false) || interface_exists($class, false)) {
					return;
				}
			}
			$paths[] = $file;
		}
	}

	/**
	 * Registering Autoloader in system autoloaders (spl_autoload_register()).
	 */
	public static function register() {
		spl_autoload_register('Autoloader::load');
	}

	/**
	 * Adding loader to a stack of loaders
	 * 
	 * @param ILoader $Loader 
	 */
	public static function add(ILoader $Loader) {
		self::$loaders[] = $Loader;
	}

}