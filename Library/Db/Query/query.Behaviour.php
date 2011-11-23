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
	
	abstract function sql();

}