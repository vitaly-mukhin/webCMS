<?php

/**
 * Main class, which describe a User entity
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;
use Core\User\Auth as Auth;
use Core\Input as Input;
use Core\Output as Output;

class User {

	const ID = 'id';

	const SET_CURRENT = true;

	const IS_LOGGED = 'is_logged';

	const REFRESHED = 'refreshed';

	/**
	 * Main (current) User object
	 *
	 * @var self
	 */
	protected static $current;

	/**
	 * Data from users storage
	 *
	 * @var User\Data
	 */
	protected $Data;

	/**
	 * Data from user_auths storage
	 *
	 * @var User\Auth
	 */
	protected $Auth;

	private function __construct() {

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
	 * Create a user account, and init it
	 *
	 * @param Input $Post data for user account
	 *
	 * @return \Core\User
	 */
	public static function reg(Input $Post) {
		$Data = new Input(array(
		                       Auth::LOGIN           => $Post->get('login'),
		                       Auth::PASSWORD        => $Post->get('password'),
		                       Auth::PASSWORD_REPEAT => $Post->get('password_repeat'),
		                       User\Data::EMAIL      => $Post->get('email'),
		                       User\Data::USERNAME   => $Post->get('username')
		                  ));

		$User   = static::f();
		$Result = static::checkReg($Data);
		if ($Result->error) {
			return $Result;
		}

		// add user to users table
		$user_id = $User->Data->reg($Data);
		if (!$user_id) {
			return $Result;
		}

		$User->setData($user_id);

		// add user to user_auths table
		$auth_id = $User->Auth->reg($user_id, $Data);
		if (!$auth_id) {
			return $Result;
		}

		$User->setAuth($Data);

		return new Result($User);
	}

	/**
	 * @param Input   $UserData
	 * @param boolean $setCurrent
	 *
	 * @return User
	 */
	public static function f(Input $UserData = null, $setCurrent = false) {
		/* @var User $User */
		$User = new static();

		$UserData = self::getFullInput($UserData);
		$User->setAuth($setCurrent ? $UserData : null);

		$User->setData($User->Auth->userId);

		if ($setCurrent == self::SET_CURRENT) {
			self::$current = $User;
		}

		return $User;
	}

	/**
	 * Prepare full set of user data
	 *
	 * @param Input $Input
	 *
	 * @return \Input
	 */
	private static function getFullInput(Input $Input = null) {
		$data = $Input ? $Input->export() : array();

		return new Input($data);
	}

	/**
	 * Set the Auth parameter, which is initiated through User\Data::f()
	 *
	 * @param Input $Data
	 *
	 * @return User
	 */
	private function setAuth(Input $Data = null) {
		$this->Auth = Auth::f();

		$this->deleteAuthInSession();

		if (is_null($Data)) {
			return $this;
		}

		$login    = $Data->get(Auth::LOGIN);
		$password = $Data->get(Auth::PASSWORD);
		$hash     = $Data->get(Auth::HASH);

		if ($login && $password) {
			$this->Auth->authByPwd($login, $password);
		} elseif ($login && $hash) {
			$this->Auth->authByHash($login, $hash);
		}

		if ($this->Auth->userId) {
			$this->saveAuth();
		}

		return $this;
	}

	public function deleteAuthInSession() {
		Session::i()->set(Session::USER,
		                  array(
		                       'is_logged' => false,
		                       'hash'      => false
		                  ));
	}

	protected function saveAuth() {
		Session::i()->set(Session::USER,
		                  array(
		                       self::IS_LOGGED => true,
		                       Auth::USER_ID   => $this->Auth->userId,
		                       Auth::LOGIN     => $this->Auth->login,
		                       Auth::HASH      => $this->Auth->hash,
		                       self::REFRESHED => date('c')
		                  ));
	}

	/**
	 * Set the Data parameter, which is initiated through User\Data::f()
	 *
	 * @param null|int $userId
	 *
	 * @return User
	 */
	private function setData($userId) {
		$this->Data = User\Data::f($userId);

		return $this;
	}

	/**
	 * Check all data, which are required for registration a new User
	 *
	 * @param Input $Post
	 *
	 * @return Result
	 */
	private static function checkReg(Input $Post) {
		$Auth   = Auth::f();
		$result = $Auth->checkReg($Post);

		if (!$result->error) {
			$Data   = User\Data::f(null);
			$result = $Data->checkReg($Post);
		}

		return $result;
	}

	/**
	 * Export data as assoc array
	 *
	 * @return array
	 */
	public function exportData() {
		return array(
			User\Data::EMAIL        => $this->Data->getEmail(),
			User\Data::USERNAME     => $this->Data->getUsername(),
			User\Data::DATE_CREATED => $this->Data->getDateCreated()
		);
	}

	/**
	 * Authenticate the User with data from $Data, and set it as current.
	 *
	 * @param Input $Data
	 *
	 * @return User
	 */
	public function auth(Input $Data) {
		return self::f($Data, self::SET_CURRENT);
	}

	/**
	 * Check if current User entity is logged
	 *
	 * @return boolean
	 */
	public function isLogged() {
		$sessionUserId = (bool)Session::i()->get(Session::USER)->get(Auth::USER_ID);
		$isLogged      = (bool)Session::i()->get(Session::USER)->get(self::IS_LOGGED);

		$result = $isLogged && ($sessionUserId == $this->getUserId());

		return $result;
	}

	/**
	 * Get the USER_ID
	 *
	 * @return string
	 */
	public function getUserId() {
		return $this->Data->getUserId();
	}

	/**
	 * Get the USERNAME
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->Data->getUsername();
	}

}
