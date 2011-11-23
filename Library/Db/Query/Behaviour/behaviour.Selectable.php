<?php

/**
 * Description of Fw_Exception
 *
 * @author Mukhenok
 */
class Fw_Db_Query_Behaviour_Selectable extends Fw_Db_Query_Behaviour {

	public function sql() {
		$params = $this->_query->export();
		$sql = array('SELECT');
		//	ADDING FIELDS LIST
		$fs = array();
		foreach ($params[Fw_Db_Query::PARAM_FROM] as $alias => $t) {
			$template = ($alias == $t['table']) ? '`%s`.%s' : '%s.%s';
 			if(is_array($t['fields'])) {
				foreach($t['fields'] as $f) {
					$fs[] = sprintf($template, $alias, $f);
				}
			} else {
				$fs[] = sprintf($template, $alias, $t['fields']);
			}
		}
		foreach($fs as $k=>$f) {
			$sql[] = $f. (($k+1) < count($fs) ? ',' : '');
		}

		$sql[] = 'FROM';

		foreach ($params[Fw_Db_Query::PARAM_FROM] as $alias => $t) {
			$sql[] = '`' . $t['table'] . '`';
			if ($alias != $t['table']) {
				$sql[] = $alias;
			}
		}

		return implode(' ', $sql);
	}

}