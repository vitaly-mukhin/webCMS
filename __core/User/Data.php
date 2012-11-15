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
	//
	const F_USER_ID = 'user_id';
	const F_EMAIL = 'email';
	const F_USERNAME = 'username';
	const F_DATE_CREATED = 'date_created';

	/**
	 * Name of user table
	 *
	 * @var string
	 */
	protected $tableName = DB_TBL_USER;

	/**
	 * Complete list of fields in users table
	 *
	 * @var array
	 */
	private static $fields = array(
		self::F_USER_ID, self::F_EMAIL, self::F_DATE_CREATED, self::F_USERNAME
	);

	/**
	 *
	 * @var Fw_Db
	 */
	protected $Db;

	/**
	 * Storage, which contains data
	 *
	 * @var Input
	 */
	protected $data = null;

	protected function __construct() {
		$this->Db = Fw_Db::i();

		return $this;
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
		if ($userId && (int) $userId > 0) {
			$Q = $this->Db->query();
			$Q->select()->from($this->tableName, self::$fields)->where(self::F_USER_ID . ' = ?', $userId);
			$result = $Q->fetchRow();
			$Data = new Input($result);
		} else {
			$Data = $this->getEmptyData();
		}

		$this->setData($Data);
	}

	/**
	 * Set up the $Data as a source of data about current class entity
	 *
	 * @param Input $Data
	 * @return User_Data 
	 */
	protected function setData(Input $Data) {
		$this->data = $Data;

		return $this;
	}

	/**
	 * Check if we have all required proper values for adding a new user record
	 *
	 * @param Input $Data
	 * @return \Result
	 */
	public function checkReg(Input $Data) {
		$result = true;

		$result = $result && filter_var($Data->get(User_Data::EMAIL), FILTER_SANITIZE_EMAIL);

		$Q = $this->Db->query()->select()->from($this->tableName, self::F_USER_ID)->where(self::F_EMAIL . ' = ?', $Data->get(User_Data::EMAIL));
		$result = $result && !$Q->fetchRow();

		return new Result(false, !$result);
	}

	/**
	 *
	 * @param Input $Data
	 * @return int
	 */
	public function reg(Input $Data) {
		$data = array(
			self::F_EMAIL => $Data->get(self::EMAIL),
			self::F_USERNAME => $Data->get(self::USERNAME)
		);

		$Q = $this->Db->query()->insert($this->tableName, $data);

		$id = $Q->fetchRow();

		return $id;
	}

	/**
	 * Unified method for retrieving data from source by field name
	 *
	 * @param string $field one of Mapper_User_Data::F_* constants
	 * @return type 
	 */
	protected function get($field) {
		return $this->data->get($field, 'field_not_found');
	}

	/**
	 * Get the EMAIL
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->get(self::F_EMAIL);
	}

	/**
	 * Get the USERNAME
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->get(self::F_USERNAME);
	}

	/**
	 * Get the DATE_CREATED
	 *
	 * @return string 
	 */
	public function getDateCreated() {
		return $this->get(self::F_DATE_CREATED);
	}

	/**
	 * Get the USER_ID
	 *
	 * @return string 
	 */
	public function getUserId() {
		return $this->get(self::F_USER_ID);
	}

}