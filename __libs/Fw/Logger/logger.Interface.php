<?php

/**
 * Description of Fw_Logger_Interface
 *
 * @author Vitaliy_Mukhin
 */
interface Fw_Logger_Interface {

	/**
	 * Preparing data for writing with self::prepareForSave(), and call self::write() for writing data
	 *
	 * @param mixed $data 
	 * @return Fw_Logger_Interface
	 */
	public function save($data);

}