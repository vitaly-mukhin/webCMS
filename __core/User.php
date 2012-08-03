<?php

/**
 * Main class, which describe a User entity
 *
 * @author Vitaliy_Mukhin
 * 
 */
class User {

	const ID = 'id';
	const SET_CURRENT = true;
	const IS_LOGGED = 'is_logged';
	const REFRESHED = 'refreshed';

	/**
	 * Main (current) User object
	 *
	 * @var User
	 */
	protected static $current;

	/**
	 * Data from users storage
	 *
	 * @var User_Data
	 */
	protected $Data;

	/**
	 * Data from user_auths storage
	 *
	 * @var User_Auth
	 */
	protected $Auth;

	private function __construct() {
		
	}

	/**
	 *
	 * @param Input $UserData
	 * @param boolean $setCurrent
	 * 
	 * @return User
	 */
	public static function f(Input $UserData = null, $setCurrent = false) {

		$UserData = self::getFullInput($UserData);

		$User = new User();

		$User->setAuth($setCurrent ? $UserData : null);

		$User->setData($User->Auth->getUserId());

		if ($setCurrent == self::SET_CURRENT) {
			self::$current = $User;
		}

		return $User;
	}

	/**
	 * Set the Data parameter, which is initiated through User_Data::f()
	 *
	 * @param null|int $userId
	 * @return User 
	 */
	private function setData($userId) {
		$this->Data = User_Data::f($userId);

		return $this;
	}

	/**
	 * Set the Auth parameter, which is initiated through User_Data::f()
	 *
	 * @param Input|int $userId
	 * @return \User 
	 */
	private function setAuth(Input $Data = null) {
		$this->Auth = User_Auth::f();

		$this->deleteAuth();

		if (is_null($Data)) {
			return $this;
		}

		if (($login = $Data->get(User_Auth::LOGIN)) && ($password = $Data->get(User_Auth::PASSWORD))) {
			$this->Auth->authByPwd($login, $password);
		} elseif (($login = $Data->get(User_Auth::LOGIN)) && ($hash = $Data->get(User_Auth::HASH))) {
			$this->Auth->authByHash($login, $hash);
		}

		if ($this->Auth->getUserId()) {
			$this->saveAuth();
		}

		return $this;
	}

	/**
	 * Get the current User entity
	 *
	 * @return User|null 
	 */
	public static function curr() {
		return empty(self::$current) ? null : self::$current;
	}

	/**
	 * Export data as assoc array
	 * 
	 * @return array
	 */
	public function exportData() {
		return array(
			User_Data::EMAIL => $this->Data->getEmail(),
			User_Data::USERNAME => $this->Data->getUsername(),
			User_Data::DATE_CREATED => $this->Data->getDateCreated()
		);
	}

	/**
	 * Create a user account, and init it
	 *
	 * @param Input $Post data for user account
	 * 
	 * @return User
	 */
	public static function reg(Input $Post) {
		$Data = new Input(array(
					User_Auth::LOGIN => $Post->get('login'),
					User_Auth::PASSWORD => $Post->get('password'),
					User_Auth::PASSWORD_REPEAT => $Post->get('password_repeat'),
					User_Data::EMAIL => $Post->get('email'),
					User_Data::USERNAME => $Post->get('username')
				));

		$User = User::f();

		if (!self::checkReg($Data)) {
			return null;
		}

		// add user to users table
		$user_id = $User->Data->reg($Data);
		if (!$user_id) {
			return null;
		}

		$User->setData($user_id);

		// add user to user_auths table
		$auth_id = $User->Auth->reg($user_id, $Data);
		if (!$auth_id) {
			return null;
		}

		$User->setAuth($Data);

		return $User;
	}

	/**
	 * Check all data, which are required for registrating a new User
	 *
	 * @param Input $Post
	 * @return boolean 
	 */
	private static function checkReg(Input $Post) {
		$result = true;

		if ($result) {
			$Auth = User_Auth::f();
			$result = $Auth->checkReg($Post);
		}

		if ($result) {
			$Data = User_Data::f(null);
			$result = $Data->checkReg($Post);
		}

		return $result;
	}

	/**
	 * Prepare full set of user data
	 *
	 * @param Input $Input
	 * @return \Input 
	 */
	private static function getFullInput(Input $Input = null) {
		$data = $Input ? $Input->export() : array();

		return new Input($data);
	}

	/**
	 * Authenticate the User with data from $Data, and set it as current.
	 *
	 * @param Input $Data
	 * @return User 
	 */
	public function auth(Input $Data) {
		return self::f($Data, self::SET_CURRENT);
	}

	public function deleteAuth() {
		Session::i()->set(Session::USER, array(
			'is_logged' => false,
			'hash' => false
		));
	}

	protected function saveAuth() {
		Session::i()->set(Session::USER, array(
			self::IS_LOGGED => true,
			User_Auth::USER_ID => $this->Auth->getUserId(),
			User_Auth::LOGIN => $this->Auth->getLogin(),
			User_Auth::HASH => $this->Auth->getHash(),
			self::REFRESHED => date('c')
		));
	}

	/**
	 * Check if current User entity is logged
	 *
	 * @return boolean
	 */
	public function isLogged() {
		$sessionUserId = (bool) Session::i()->get(Session::USER)->get(User_Auth::USER_ID);
		$isLogged = (bool) Session::i()->get(Session::USER)->get(self::IS_LOGGED);

		$result = $isLogged && ($sessionUserId == $this->getUserId());

		return $result;
	}

	/**
	 * Get the USERNAME
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->Data->getUsername();
	}

	/**
	 * Get the USER_ID
	 *
	 * @return string
	 */
	public function getUserId() {
		return $this->Data->getUserId();
	}

}