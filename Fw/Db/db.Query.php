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
	 * @var Fw_Db_Query_Behaviour
	 */
	protected $_behaviour;

	/**
	 *
	 * @var PDO
	 */
	protected $_connection;

	/**
	 * All params of query
	 * array(
	 * 		self::PARAM_FROM => array(
	 * 			'alias' => array(
	 * 				table => 'table_name',
	 * 				fields => array('field1', 'field2', ...) | '*'
	 * 			)
	 * 		),
	 * 		[
	 * 		self::PARAM_WHERE => array(
	 * 			Fw_Db_Query_Where, 
	 * 			[Fw_Db_Query_Where]
	 * 		)
	 * 		]
	 * )
	 * 
	 * @var array
	 */
	protected $_params = array();

	/**
	 *
	 * @var PDOStatement Prepared statement
	 */
	protected $_Stmt;

	/**
	 *
	 * @var array Result of executing a query 
	 */
	protected $_result;

	const PARAM_FROM = 'from';
	const PARAM_JOIN = 'join';
	const PARAM_WHERE = 'where';
	const PARAM_VALUES = 'values';
	const PARAM_ORDER_BY = 'order';
	const PARAM_LIMIT = 'limit';
	const LIMIT_START = 'start';
	const LIMIT_COUNT = 'count';

	public function __construct(Fw_Db $db, $sql = null, $binds = null) {
		$this->_db = $db;

		if (!empty($sql) && is_string($sql)) {
			$this->_sql = $sql;
			$this->_binds = $binds;
		}
	}

	public function __get($name) {
		switch ($name) {
			case 'sql':
				if (empty($this->_sql)) {
					$this->_sql = $this->getBehaviour()->sql;
				}
				return $this->_sql;
			case 'binds':
				if (empty($this->_binds)) {
					$this->_binds = $this->getBehaviour()->binds;
				}
				return $this->_binds;
		}
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
	 * @return Fw_Db_Query 
	 */
	public function insert($table = null, $values = null) {
		$this->_behaviour = new Fw_Db_Query_Behaviour_Insert($this);
		if (!empty($table)) {
			$this->into($table, array_keys($values));
			$this->values(array_values($values));
		}
		return $this;
	}

	/**
	 *
	 * @return Fw_Db_Query 
	 */
	public function update() {
		$this->_behaviour = new Fw_Db_Query_Behaviour_Update($this);
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
	 * Adding one more 'from' value
	 *
	 * @param string|array $table
	 * @param array $fields
	 * @return Fw_Db_Query 
	 */
	public function from($table, $fields = null) {
		if (empty($table)) {
			throw new Fw_Exception_Db_Query('Empty FROM parameter');
		}

		if (empty($this->_params[self::PARAM_FROM])) {
			$this->_params[self::PARAM_FROM] = array();
		}

		$alias = $table_name = $table;
		if (is_array($table)) {
			reset($table);
			$alias = key($table);
			$table_name = $table[$alias];
		}
		$this->_params[self::PARAM_FROM][$alias] = array('table' => $table_name, 'fields' => (!empty($fields) ? $fields : '*'));

		return $this;
	}

	/**
	 * Alias of from() method
	 *
	 * @param string|array $table
	 * @param array $fields
	 * @return Fw_Db_Query 
	 */
	public function into($table, $fields = null) {
		return $this->from($table, $fields);
	}

	/**
	 *
	 * @param mix $condition
	 * @param mix $value
	 * @return Fw_Db_Query 
	 */
	public function where($condition) {
		$this->_params[self::PARAM_WHERE][md5($condition)] = new Fw_Db_Query_Where($condition);
		$args = func_get_args();
		array_shift($args);
		if ($args) {
			foreach ($args as $v) {
				$this->_params[self::PARAM_WHERE][md5($condition)]->pushValue($v);
			}
		}

		return $this;
	}

	/**
	 *
	 * @return Fw_Db_Query 
	 */
	public function values() {
		$params = func_get_args();
		foreach ($params as $p) {
			$this->_params[self::PARAM_VALUES][] = $p;
		}
		return $this;
	}

	/**
	 * Method used for retrieving parameters from Query to Behaviour
	 *
	 * @return mix
	 */
	public function export($param = null) {
		if ($param !== null) {
			if (isset($this->_params[$param])) {
				return $this->_params[$param];
			}
			throw new Fw_Exception_Db_Query('Unknown parameter: ' . $param);
		}
		return $this->_params;
	}

	public function join($table, $condition, $fields = null) {
		if (empty($table)) {
			throw new Fw_Exception_Db_Query_Join('Empty from parameter');
		}

		if (empty($this->_params[self::PARAM_JOIN])) {
			$this->_params[self::PARAM_JOIN] = array();
		}

		$alias = $table_name = $table;
		if (is_array($table)) {
			reset($table);
			$alias = key($table);
			$table_name = $table[$alias];
		}
		$this->_params[self::PARAM_JOIN][$alias] = array('table' => $table_name, 'condition' => $condition, 'fields' => (!empty($fields) ? $fields : '*'));

		return $this;
	}

	protected function _execute($options = array()) {
		$this->_connection = empty($this->_connection) ? $this->_db->pointer : $this->_connection;

		$this->_Stmt = $this->_connection->prepare($this->sql, $options);
		return $this->_Stmt->execute($this->binds);
	}

	public function fetch() {
		if (!$this->_execute()) {
			$message = $this->_Stmt->errorInfo();
			$code = $this->_Stmt->errorCode();
			throw new Fw_Exception_Db_Query($message, $code);
		}

		if ($this->getBehaviour() instanceof Fw_Db_Query_Behaviour_Insert) {
			return $this->_connection->lastInsertId();
		}

		$this->_result = array();
		while ($row = $this->_Stmt->fetch(PDO::FETCH_ASSOC)) {
			$this->_result[] = $row;
		}

		return $this->_result;
	}

	public function fetchRow() {
		if (!($result = $this->_execute())) {
			throw new Fw_Exception_Db_Query('some problem with query');
		}

		if ($this->getBehaviour() instanceof Fw_Db_Query_Behaviour_Insert) {
			return $this->_connection->lastInsertId();
		}

		$this->_result = $this->_Stmt->fetch(PDO::FETCH_ASSOC);

		return $this->_result;
	}

	/**
	 *
	 * @param string|array $data
	 * @return Fw_Db_Query
	 * @throws Fw_Exception_Db_Query_Join 
	 */
	public function orderBy($data) {
		if (empty($data)) {
			throw new Fw_Exception_Db_Query_Join('Empty orderBy parameter');
		}

		if (empty($this->_params[self::PARAM_ORDER_BY])) {
			$this->_params[self::PARAM_ORDER_BY] = array();
		}

		$this->_params[self::PARAM_ORDER_BY] = $data;

		return $this;
	}

	public function limit($limit, $start = 0) {

		if (empty($this->_params[self::PARAM_LIMIT])) {
			$this->_params[self::PARAM_LIMIT] = array();
		}

		$this->_params[self::PARAM_LIMIT][self::LIMIT_COUNT] = $limit;
		$this->_params[self::PARAM_LIMIT][self::LIMIT_START] = $start;

		return $this;
	}

}