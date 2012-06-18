<?php

/**
 * Description of Fw_Logger
 *
 * @author Vitaliy_Mukhin
 */
class Fw_Logger_Abstract implements Fw_Logger_Interface {

	/**
	 *
	 * @var Fw_Config
	 */
	protected $_Config;

	/**
	 *
	 * @var array
	 */
	protected $_data;

	public function __construct(Fw_Config $Config) {
		$this->_Config = $Config;
	}

	/**
	 * Preparing data for writing with self::_prepare(), and call self::_write() for writing data
	 *
	 * @param mixed $data 
	 * @return Fw_Logger_Abstract
	 */
	public function save($data) {
		$this->prepareForWrite($data);

		return $this->write();
	}

	/**
	 *
	 * @param mixed $data 
	 */
	protected function prepareForWrite($data) {
		$this->_data = $data;
	}

	/**
	 *
	 * @return Fw_Logger_Abstract
	 */
	protected function write() {
		return $this;
	}
}