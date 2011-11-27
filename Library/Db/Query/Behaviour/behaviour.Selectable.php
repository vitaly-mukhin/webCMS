<?php

/**
 * Description of Fw_Exception
 *
 * @author Mukhenok
 */
class Fw_Db_Query_Behaviour_Selectable extends Fw_Db_Query_Behaviour {

	protected function _build() {
		$params = $this->_query->export();
		$sql = array('SELECT');
		$binds = array();
		$this->_buildFields($sql, $params);
		$this->_buildFrom($sql, $params);
		$this->_buildWhere($sql, $params, $binds);



		$this->_sql = implode(' ', $sql);
		$this->_binds = $binds;
	}

	protected function _buildFields(&$sql, $params) {
		$fs = array();
		foreach ($params[Fw_Db_Query::PARAM_FROM] as $alias => $t) {
			$template = ($alias == $t['table']) ? '`%s`.%s' : '%s.%s';
			if (is_array($t['fields'])) {
				foreach ($t['fields'] as $f) {
					$fs[] = sprintf($template, $alias, $f);
				}
			} else {
				$fs[] = sprintf($template, $alias, $t['fields']);
			}
		}
		$this->_addSymbolAndPush($sql, $fs);
	}

	/**
	 *
	 * @param array $sql
	 * @param type $params 
	 */
	protected function _buildFrom(&$sql, $params) {
		$sql[] = 'FROM';

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

}