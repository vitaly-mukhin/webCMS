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

	protected function _addSymbolAndPush(&$sql, $array, $symbol = ',') {
		$l = count($array); //	array(f1, f2); $l = 2;
		for ($i = 0; $i < $l; $i++) {
			$sql[] = $array[$i] . (($i + 1) < $l ? $symbol : '');
		}
	}

}