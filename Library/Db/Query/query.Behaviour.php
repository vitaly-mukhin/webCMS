<?php

/**
 * Description of Fw_Exception
 *
 * @author Mukhenok
 */
abstract class Fw_Db_Query_Behaviour {

	/**
	 *
	 * @var Fw_Db_Query
	 */
	protected $_query;

	public function __construct(Fw_Db_Query $query) {
		$this->_query = $query;
	}

	protected function _build() {
		
	}

	protected function _addSymbolAndPush(&$sql, $array, $symbol = ',') {
		$l = count($array); //	array(f1, f2); $l = 2;
		for ($i = 0; $i < $l; $i++) {
			$sql[] = $array[$i] . (($i + 1) < $l ? $symbol : '');
		}
	}

}