<?php

/**
 * Description of Fw_Exception
 *
 * @author Mukhenok
 */
class Fw_Db_Query_Behaviour_Insert extends Fw_Db_Query_Behaviour {

	protected function _build() {
		$params = $this->_query->export();
		$sql = array('INSERT');
		$binds = array();
		$this->_buildInto($sql, $params);
		$this->_buildFields($sql, $params);
		$this->_buildValues($sql, $params, $binds);



		$this->_sql = implode(' ', $sql);
		$this->_binds = $binds;
	}

	/**
	 *
	 * @param array $sql
	 * @param type $params 
	 */
	protected function _buildInto(&$sql, $params) {
		$sql[] = 'INTO';

		$fs = array();
		foreach ($params[Fw_Db_Query::PARAM_FROM] as $alias => $t) {
			$fs[] = '`' . $t['table'] . '`';
		}

		$this->_addSymbolAndPush($sql, $fs);
	}

	protected function _buildFields(&$sql, $params) {
		$sql[] = '(';
		$fs = array();
		foreach ($params[Fw_Db_Query::PARAM_FROM] as $t) {
			if (is_array($t['fields'])) {
				foreach ($t['fields'] as $f) {
					$fs[] = sprintf('`%s`', $f);
				}
			} else {
				throw new Fw_Exception_Db_Query('Cannot build INSERT query with non-array fields list');
			}
		}
		$this->_addSymbolAndPush($sql, $fs);
		$sql[] = ')';
	}

	/**
	 *
	 * @param array $sql
	 * @param type $params 
	 */
	protected function _buildValues(&$sql, $params, &$binds) {
		if (!isset($params[Fw_Db_Query::PARAM_VALUES])) {
			return;
		}

		$sql[] = 'VALUES';

		$fs = array();
		foreach ($params[Fw_Db_Query::PARAM_VALUES] as $vs) {
			$fs[] = '(' . implode(', ', array_fill(0, count($vs), '?')) . ')';
			foreach ($vs as $v) {
				$binds[] = $v;
			}
		}

		$this->_addSymbolAndPush($sql, $fs);
	}

}