<?php

/**
 * Description of User
 *
 * @author Vitaliy_Mukhin
 */
class Mapper_User_Data extends Mapper_User {

	const F_USER_ID = 'user_id';
	const F_EMAIL = 'email';
	const F_USERNAME = 'username';
	const F_DATE_CREATED = 'date_created';

	protected $tableName = 'users';

	/**
	 *
	 * @var array
	 */
	private static $fields = array(
		self::F_USER_ID, self::F_EMAIL, self::F_DATE_CREATED, self::F_USERNAME
	);

	/**
	 *
	 * @param int $userId
	 * @return \Input 
	 */
	public function byId($userId) {
		$Q = $this->Db->query();

		$Q->select()->from($this->tableName, self::$fields)->where(self::F_USER_ID . ' = ?', $userId);

		$result = $Q->fetchRow();

		return new Input($result);
	}

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