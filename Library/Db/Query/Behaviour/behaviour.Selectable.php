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
		$this->_addCommaAndPush($sql, $fs);

		$sql[] = 'FROM';

		$fs = array();
		foreach ($params[Fw_Db_Query::PARAM_FROM] as $alias => $t) {
			$a = '`' . $t['table'] . '`';
			if ($alias != $t['table']) {
				$a .= ' '.$alias;
			}
			$fs[] = $a;
		}
		$this->_addCommaAndPush($sql, $fs);

		return implode(' ', $sql);
	}

}