<?php

/**
 * Description of Userdata
 *
 * @author Vitaliy_Mukhin
 */
class User_Data {

	const EMAIL = 'email';
	const USERNAME = 'username';

	/**
	 *
	 * @var Input
	 */
	protected $userData = null;

	/**
	 *
	 * @var Mapper_User_Data
	 */
	protected $Mapper;

	protected function __construct() {
		$this->Mapper = new Mapper_User_Data;
	}

	/**
	 *
	 * @param int|null $userId
	 * @return User_Data 
	 */
	public static function f($userId) {
		$UserData = new static();

		$UserData->init($userId);

		return $UserData;
	}

	/**
	 *
	 * @param int $userId
	 * @return \Input 
	 */
	protected function getEmptyData() {
		return new Input(array());
	}

	/**
	 *
	 * @param int|null $userId 
	 */
	protected function init($userId) {
		$Data = ($userId && (int) $userId > 0) ? $this->Mapper->byId((int) $userId) : $this->getEmptyData();

		$this->setData($Data);
	}

	/**
	 *
	 * @param Input $Data
	 * @return \User_Data 
	 */
	protected function setData(Input $Data) {
		$this->userData = $Data;

		return $this;
	}

	/**
	 *
	 * @param Input $Data
	 * @return int
	 */
	public function reg(Input $Data) {
		return $this->Mapper->reg($Data);
	}

	protected function get($field) {
		return $this->userData->get($field);
	}

	public function getEmail() {
		return $this->get(Mapper_User_Data::F_EMAIL);
	}

	public function getUsername() {
		return $this->get(Mapper_User_Data::F_USERNAME);
	}

	public function getDateCreated() {
		return $this->get(Mapper_User_Data::F_DATE_CREATED);
	}

}