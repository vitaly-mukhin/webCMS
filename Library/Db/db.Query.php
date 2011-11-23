<?php

/**
 * Description of Fw_Exception
 *
 * @author Mukhenok
 */
class Fw_Db_Query {

	/**
	 *
	 * @var Fw_Db
	 */
	protected $_db;

	/**
	 *
	 * @var string
	 */
	protected $_table;

	/**
	 *
	 * @var Fw_Db_Query_Behaviour
	 */
	protected $_behaviour;
	protected $_params = array();

	const PARAM_FROM = 'from';

	public function __construct(Fw_Db $db, $table = null) {
		$this->_db = $db;
		$this->_table = $table;
	}

	/**
	 *
	 * @return Fw_Db_Query 
	 */
	public function select() {
		$this->_behaviour = new Fw_Db_Query_Behaviour_Selectable($this);
		return $this;
	}

	/**
	 *
	 * @return Fw_Db_Query_Behaviour
	 */
	public function getBehaviour() {
		return $this->_behaviour;
	}

	/**
	 *
	 * @param string|array $table
	 * @return Fw_Db_Query 
	 */
	public function from($table, $fields=null) {
		if (empty($table)) {
			throw new Fw_Exception_Db_Query_From('Empty from parameter');
		}

		if(empty($this->_params[self::PARAM_FROM])) {
			$this->_params[self::PARAM_FROM] = array();
		}
		
		$alias = $table_name = $table;
		if(is_array($table)) {
			reset($table);
			$alias = key($table);
			$table_name = $table[$alias];
		}
		$this->_params[self::PARAM_FROM][$alias] = array('table' => $table_name, 'fields' => (!empty($fields) ? $fields : '*'));

		return $this;
	}

	/**
	 * Method used for retrieving parameters from Query to Behaviour
	 *
	 * @return mix
	 */
	public function export($param=null) {
		if ($param !== null) {
			if (isset($this->_params[$param])) {
				return $this->_params[$param];
			}
			throw new Fw_Exception_Db_Query('Unknown parameter: ' . $param);
		}
		return $this->_params;
	}

}