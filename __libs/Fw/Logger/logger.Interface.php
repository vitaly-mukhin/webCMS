<?php

/**
 * Description of Fw_Logger_Interface
 *
 * @author Vitaliy_Mukhin
 */
interface Fw_Logger_Interface {

//	public function __construct(Fw_Config $Config);

	/**
	 * Preparing data for writing with self::_prepare(), and call self::_write() for writing data
	 *
	 * @param mix $data 
	 * @return Fw_Logger_Abstract
	 */
	public function save($data);

	/**
	 *
	 * @return mix
	 */
	public function getData();
}