<?php

/**
 * Description of Config
 *
 * @author Vitaly Mukhin
 */
class Config {
	
	/**
	 * Full filename for config file
	 *
	 * @var filename
	 */
	protected $_file = '';
	
	protected $_data = array();

	public function __construct($file_name) {
		if(is_array($file_name)) {
			$this->_data = $file_name;
		} else {
			$this->_file = $file_name;
			$this->_data = $this->_getConfigData();
		}
	}
	
	protected function _getConfigData() {
		ob_start();
		$data = include($this->_file);
		ob_end_clean();
		return $data;
	}
	
	public function __get($name) {
		if(isset($this->_data[$name])) {
			if(is_array($this->_data[$name])) {
				return new static($this->_data[$name]);
			} else {
				return $this->_data[$name];
			}
		}
		
		throw new Exception('Unknown config property: '.$name);
	}
	
	public function __isset($name) {
		return isset($this->_data[$name]);
	}

}