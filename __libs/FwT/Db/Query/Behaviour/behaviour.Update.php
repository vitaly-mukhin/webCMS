<?php

/**
 * Description of Fw_Exception
 *
 * @author Mukhenok
 */
class Fw_Db_Query_Behaviour_Update extends Fw_Db_Query_Behaviour {

	protected function _build() {
		$params = $this->_query->export();
		$sql = array('UPDATE');
		$binds = array();
		$this->_buildFrom($sql, $params);
		$this->_buildValues($sql, $params, $binds);
		$this->_buildWhere($sql, $params, $binds);

		$this->_sql = implode(' ', $sql);
		$this->_binds = $binds;
	}

	/**
	 *
	 * @param array $sql
	 * @param type $params 
	 */
	protected function _buildValues(&$sql, $params, &$binds) {
		$sql[] = 'SET';
		$fs = array();
		foreach ($params[Fw_Db_Query::PARAM_VALUES] as $vs) {
			foreach ($vs as $k => $v) {
				$fs[] = '`' . $k . '` = ?';
				$binds[] = $v;
			}
		}

		$this->_addSymbolAndPush($sql, $fs);
	}

}