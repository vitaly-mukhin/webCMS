<?php

/**
 * Class, which should be included into every entity of User.
 * It contains all (except auth) info about user.
 *
 * @author Vitaliy_Mukhin
 */
class User_Data {

	const EMAIL = 'email';
	const USERNAME = 'username';
	const DATE_CREATED = 'date_created';

	/**
	 * Storage, which contains data
	 *
	 * @var Input
	 */
	protected $userData = null;

	/**
	 * DataMapper which is responsible for operating with DB or any other storage
	 *
	 * @var Mapper_User_Data
	 */
	protected $Mapper;

	protected function __construct() {
		$this->Mapper = new Mapper_User_Data;
	}

	/**
	 * Factory method for creating a new entity, and initiating it with default data
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
	 * Returns empty Input for unlogged user
	 *
	 * @param int $userId
	 * @return Input 
	 */
	protected function getEmptyData() {
		return new Input(array());
	}

	/**
	 * Init procedure for every entity of this class
	 *
	 * @param int|null $userId 
	 */
	protected function init($userId) {
		$Data = ($userId && (int) $userId > 0) ? $this->Mapper->byId((int) $userId) : $this->getEmptyData();

		$this->setData($Data);
	}

	/**
	 * Set up the $Data as a source of data about current class entity
	 *
	 * @param Input $Data
	 * @return User_Data 
	 */
	protected function setData(Input $Data) {
		$this->userData = $Data;

		return $this;
	}

	/**
	 * Check if we have all required proper values for adding a new user record
	 *
	 * @param Input $Data
	 * @return boolean
	 */
	public function checkReg(Input $Data) {
		$result = true;
		
		$result = $result && filter_var($Data->get(User_Data::EMAIL), FILTER_SANITIZE_EMAIL);
		
		$result = $result && $this->Mapper->checkReg($Data);
		
		return $result;
	}

	/**
	 *
	 * @param Input $Data
	 * @return int
	 */
	public function reg(Input $Data) {
		return $this->Mapper->reg($Data);
	}

	/**
	 * Unified method for retrieving data from source by field name
	 *
	 * @param string $field one of Mapper_User_Data::F_* constants
	 * @return type 
	 */
	protected function get($field) {
		return $this->userData->get($field, 'field_not_found');
	}

	/**
	 * Get the EMAIL
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->get(Mapper_User_Data::F_EMAIL);
	}

	/**
	 * Get the USERNAME
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->get(Mapper_User_Data::F_USERNAME);
	}

	/**
	 * Get the DATE_CREATED
	 *
	 * @return string 
	 */
	public function getDateCreated() {
		return $this->get(Mapper_User_Data::F_DATE_CREATED);
	}

	/**
	 * Get the USER_ID
	 *
	 * @return string 
	 */
	public function getUserId() {
		return $this->get(Mapper_User_Data::F_USER_ID);
	}

}