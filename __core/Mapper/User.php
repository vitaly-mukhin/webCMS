<?php

/**
 * Description of User
 *
 * @author Vitaliy_Mukhin
 */
abstract class Mapper_User {

	protected $tableName;

	/**
	 *
	 * @var Fw_Db
	 */
	protected $Db;

	public function __construct() {
		$this->setConnector(Fw_Db::i());
	}

	/**
	 *
	 * @param Fw_Db $Db
	 * @return \Mapper_User 
	 */
	private function setConnector(Fw_Db $Db) {
		$this->Db = $Db;

		return $this;
	}

}