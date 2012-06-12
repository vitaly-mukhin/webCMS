<?php

/**
 * Description of User
 *
 * @author Vitaliy_Mukhin
 */
class Mapper_User {
	
	/**
	 *
	 * @var Mapper_User 
	 */
	private static $instance;
	
	/**
	 *
	 * @var Fw_Db
	 */
	private $Db;
	
	/**
	 *
	 * @return Mapper_User
	 */
	public static function i() {
		if (!empty(self::$instance)) {
			return self::$instance;
		}
		
		$i = new self();
		
		$i->setConnector(Fw_Db::i());
		
		self::$instance = $i;
		
		return self::$instance;
	}
	
	
	private function setConnector(Fw_Db $Db) {
		$this->Db = $Db;
		
		return $this;
	}
	
}