<?php

/**
 * Description of Fw_Logger_Db
 *
 * @author Vitaly Mukhin
 */
class Fw_Logger_Db extends Fw_Logger {

	public function __construct(Fw_Db $adapter, $Config, $callback = null) {
		$this->_adapter = $adapter;
		$this->_options = ($Config instanceof Fw_Config) ? $Config->toArray() : $Config;
		$this->setDecorator($callback);
	}
	
	public function log($income) {
		$decorator = $this->_decorator;
		return $this->_adapter->query()->insert($this->_options['table'], $decorator($income));
	}
	
	/**
	 *
	 * @param Fw_Db_Query $query 
	 */
	public function push($query) {
		if(is_array($query)) {
			$query = $this->log($query);
		}
		
		return $query->fetchRow();
	}

}