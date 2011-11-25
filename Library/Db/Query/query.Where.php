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
	protected $_value;

	/**
	 * Type of value
	 *
	 * @var int PDO::PARAM_*
	 */
	protected $_cast;

	public function __construct($condition, $value = null, $cast = PDO::PARAM_STR) {
		$this->_condition = $condition;
		$this->_value = $value;
		$this->_cast = $cast;
	}

	public function __get($name) {
		switch ($name) {
			case 'condition':
			case 'value':
			case 'cast':
				return $this->{'_' . $name};
		}
	}

	public function appendToQuery(&$fs, &$binds) {
		$fs[] = sprintf('(%s)', $this->_condition);
		if(strpos($this->_condition, '?')) {
			$binds[] = $this->_value;
		}
	}

}