<?php

/**
 * Class for mapping data for User_Data
 *
 * @author Vitaliy_Mukhin
 */
class Mapper_User_Data extends Mapper_User {

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
	 * Get a single record by user_id
	 *
	 * @param int $userId
	 * @return Input 
	 */
	public function byId($userId) {
		$Q = $this->Db->query();

		$Q->select()->from($this->tableName, self::$fields)->where(self::F_USER_ID . ' = ?', $userId);

		$result = $Q->fetchRow();

		return new Input($result);
	}

	/**
	 * Check if we have unique user data (within our db)
	 *
	 * @param Input $Data
	 * @return boolean 
	 */
	public function checkReg(Input $Data) {
		$Q = $this->Db->query()->select()->from($this->tableName, self::$fields)->where(self::F_EMAIL . ' = ?', $Data->get(User_Data::EMAIL));

		$resutl = $Q->fetchRow();

		return empty($resutl);
	}

	/**
	 * Add a record to user table.
	 *
	 * @param Input $Data
	 * @return int|null
	 */
	public function reg(Input $Data) {
		$data = array(
			self::F_EMAIL => $Data->get(User_Data::EMAIL),
			self::F_USERNAME => $Data->get(User_Data::USERNAME)
		);

		$Q = $this->Db->query();

		$Q->insert($this->tableName, $data);

		$id = $Q->fetchRow();

		return $id;
	}

}