<?php

/**
 * Description of Auth
 *
 * @author Vitaliy_Mukhin
 */
class User_Auth {
	//

	const F_ID = 'id';
	const F_LOGIN = 'login';
	const F_PASSWORD = 'password';
	const F_HASH = 'hash';

	//
	const LOGIN = 'login';
	const PASSWORD = 'password';
	const PASSWORD_REPEAT = 'password_repeat';

	/**
	 *
	 * @var Input
	 */
	protected $userAuth = null;

	protected function __construct() {
		
	}

	/**
	 *
	 * @param Input|int $userId
	 * @return User_Data 
	 */
	public static function f($userId) {
		$UserAuth = new self();

		$UserAuth->init($userId);

		return $UserAuth;
	}

	/**
	 *
	 * @param int $userId
	 * @return \Input 
	 */
	protected function getUserAuth($userId) {
		$array = array(
			self::F_ID => $userId,
			self::F_LOGIN => 'user_login',
			self::F_PASSWORD => 'ahaha'
		);

		$array[self::F_HASH] = sha1($array[self::F_LOGIN] . $array[self::F_PASSWORD]);

		return new Input($array);
	}

	protected function init($userId) {

		$Data = ($userId instanceof Input) ? $userId : $this->getUserAuth($userId);

		$this->userAuth = $Data;
	}

}