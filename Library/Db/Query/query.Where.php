<?php

/**
 * Description of Fw_Db_Query_Where
 *
 * @author Vitaly Mukhin
 */
class Fw_Db_Query_Where {
	
	/**
	 *
	 * @var mix
	 */
	protected $_condition;
	
	/**
	 *
	 * @var mix
	 */
	protected $_value;

	public function __construct($condition, $value) {
		$this->_condition = $condition;
		$this->_value = $value;
	}

}