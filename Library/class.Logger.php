<?php

/**
 * Description of Logger
 *
 * @author Vitaly Mukhin
 */
abstract class Fw_Logger {

	/**
	 *
	 * @var mix
	 */
	protected $_adapter;

	/**
	 *
	 * @var array
	 */
	protected $_options;
	protected $_decorator;

	public function __get($name) {
		switch ($name) {
			case 'decorator':
				return $this->_decorator;
		}
	}

	/**
	 *
	 * @param callback $callback
	 * @return Fw_Logger 
	 */
	public function setDecorator($callback) {
		if(!is_null($callback) && is_callable($callback)) {
			$this->_decorator = $callback;
		}
		return $this;
	}
	
	abstract public function log($income);
	
	abstract public function push($record);

}