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
	 * @param mix $data 
	 * @return Fw_Logger_Abstract
	 */
	public function save($data) {
		$this->_prepare($data);

		return $this->_write();
	}

	/**
	 *
	 * @return mix
	 */
	public function getData() {
		return $this->_data;
	}

	/**
	 *
	 * @param mix $data 
	 */
	protected function _prepare($data) {
		$this->_data = $data;
	}

	/**
	 *
	 * @return Fw_Logger_Abstract
	 */
	protected function _write() {
		return $this;
	}
}