<?php

/**
 * Description of User
 *
 * @author Vitaliy_Mukhin
 */
class Mapper_User_Auth extends Mapper_User {

	const F_AUTH_ID = 'auth_id';
	const F_USER_ID = 'user_id';
	const F_LOGIN = 'login';
	const F_HASH = 'hash';

	/**
	 *
	 * @var string
	 */
	protected $tableName = 'user_auths';

	/**
	 *
	 * @var array
	 */
	private static $fields = array(
		self::F_AUTH_ID, self::F_USER_ID, self::F_LOGIN, self::F_HASH
	);

	/**
	 *
	 * @param string $login
	 * @param string $hash
	 * @return \Input 
	 */
	public function byHash($login, $hash) {
		$Q = $this->Db->query();

		$Q->select()->from($this->tableName, self::$fields)->where(self::F_LOGIN . ' = ?', $login)->where(self::F_HASH . ' = ?', $hash);

		$result = $Q->fetchRow();

		return new Input((array)$result);
	}

	public function reg(Input $Data) {
		$data = array(
			self::F_USER_ID => $Data->get(self::F_USER_ID),
			self::F_LOGIN => $Data->get(self::F_LOGIN),
			self::F_HASH => $Data->get(self::F_HASH)
		);

		$Q = $this->Db->query();

		$Q->insert($this->tableName, $data);

		$id = $Q->fetchRow();

		return $id;
	}

}