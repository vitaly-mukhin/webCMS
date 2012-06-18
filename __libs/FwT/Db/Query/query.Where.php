<?php

/**
 * Description of Fw_Db_Query_Where
 *
 * @author Vitaly Mukhin
 * 
 * @property-read mix $condition
 * @property-read mix $value
 * @property-read mix $cast
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
	protected $_values = array();

	/**
	 * Type of value
	 *
	 * @var int PDO::PARAM_*
	 */
	protected $_cast;

	public function __construct($condition) {
		$this->_condition = $condition;
		if (($a = func_get_args()) && count($a) > 1) {
			array_shift($a);
			foreach ($a as $v) {
				$this->pushValue($v);
			}
		}
	}

	public function __get($name) {
		switch ($name) {
			case 'condition':
			case 'value':
				return $this->{'_' . $name};
		}
	}

	public function pushValue($value) {
		$this->_values[] = $value;
	}

	public function appendToQuery(&$fs, &$binds) {
		$fs[] = sprintf('(%s)', $this->_condition);
		if ($this->_values) {
			foreach ($this->_values as $v) {
				$binds[] = $v;
			}
		}
	}

}