<?php

/**
 * Description of Fw_Exception
 *
 * @author Mukhenok
 */
class Fw_Db_Query_Behaviour_Delete extends Fw_Db_Query_Behaviour {

	protected function _build() {
		$params = $this->_query->export();
		$sql = array('DELETE FROM');
		$binds = array();
		$this->_buildFrom($sql, $params);
		$this->_buildWhere($sql, $params, $binds);

		$this->_sql = implode(' ', $sql);
		$this->_binds = $binds;
	}

}