<?php

/**
 * Description of Fw_Exception
 *
 * @author Mukhenok
 * 
 * @property-read string $sql
 * @property-read array $binds
 */
abstract class Fw_Db_Query_Behaviour {

	/**
	 *
	 * @var Fw_Db_Query
	 */
	protected $_query;
	protected $_sql;
	protected $_binds;

	public function __construct(Fw_Db_Query $query) {
		$this->_query = $query;
	}

	public function __get($name) {
		switch ($name) {
			case 'sql':
			case 'binds':
				if (empty($this->_sql)) {
					$this->_build();
				}
				return $this->{'_' . $name};
		}
	}

	protected function _build() {
		
	}

	/**
	 *
	 * @param array $sql
	 * @param type $params 
	 */
	protected function _buildFrom(&$sql, $params) {
		$fs = array();
		foreach ($params[Fw_Db_Query::PARAM_FROM] as $alias => $t) {
			$a = '`' . $t['table'] . '`';
			if ($alias != $t['table']) {
				$a .= ' ' . $alias;
			}
			$fs[] = $a;
		}

		$this->_addSymbolAndPush($sql, $fs);
	}

	/**
	 *
	 * @param array $sql
	 * @param type $params 
	 */
	protected function _buildWhere(&$sql, $params, &$binds) {
		if (!isset($params[Fw_Db_Query::PARAM_WHERE])) {
			return;
		}

		$sql[] = 'WHERE';

		$fs = array();
		foreach ($params[Fw_Db_Query::PARAM_WHERE] as $W) {
			/* @var $W Fw_Db_Query_Where */
			$W->appendToQuery($fs, $binds);
		}

		$this->_addSymbolAndPush($sql, $fs, ' AND');
	}

	/**
	 *
	 * @param array $sql
	 * @param type $params 
	 */
	protected function _buildOrderBy(&$sql, $params) {
		if (!isset($params[Fw_Db_Query::PARAM_ORDER_BY])) {
			return;
		}

		$fs = array();
		$sql[] = 'ORDER BY';
		$order = $params[Fw_Db_Query::PARAM_ORDER_BY];
		if (!is_array($order)) {
			$order = array($order);
		}
		foreach ($order as $k => $o) {
			$str = (is_int($k)) ? $o : $k;
			if (!is_int($k)) {
				$str .= ' ' . ($o ? 'ASC' : 'DESC');
			}
			$fs[] = $str;
		}

		$this->_addSymbolAndPush($sql, $fs);
	}

	protected function _addSymbolAndPush(&$sql, $array, $symbol = ',') {
		$l = count($array); //	array(f1, f2); $l = 2;
		for ($i = 0; $i < $l; $i++) {
			$sql[] = $array[$i] . (($i + 1) < $l ? $symbol : '');
		}
	}

}