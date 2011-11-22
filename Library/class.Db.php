<?php

/**
 * Description of class
 *
 * @author Vitaly Mukhin
 */
class Fw_Db {

	protected static $_instance;

	public static function i() {
		if(empty(static::$_instance)) {
			static::$_instance = new static();
		}

		return static::$_instance;
	}

}