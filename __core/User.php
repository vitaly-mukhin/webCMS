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
use Core\User\Data;

/**
 * Class User
 *
 * @package Core
 *
 * @property int    $user_id
 * @property string $email
 * @property string $username
 */
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

	protected static $instances = array();

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

	private function __construct($id = null, Input $UserData = null, $setCurrent = false) {
		$this->setData($id);
	}

	/**
	 * Get the current User entity
	 *
	 * @return User|null
	 */
	public static function curr() {
		return empty(self::$current) ? static::i() : self::$current;
	}

	/**
	 * Create a user account, and init it
	 *
	 * @param Input $Post data for user account
	 *
	 * @return \Core\Result
	 */
	public static function reg(Input $Post) {
		$User = static::i();

		// add user to users table
		$Result = $User->Data->reg(v([User\Data::EMAIL, User\Data::USERNAME, User\Data::PASSWORD, User\Data::PASSWORD_REPEAT], '', $Post->export()));
		if ($Result->error) return $Result;

		$User = static::i($Result->value);

		return new Result($User);
	}

	public static function i($id = null, Input $UserData = null, $setCurrent = false) {
		if (isset(static::$instances[$id])) return static::$instances[$id];

		/* @var \Core\User $User */
		$User = new static($id);
		if ($id) $User->setData($id);

		//		$UserData = $UserData ? : new Input();
		//
		//		$User->setAuth($setCurrent ? $UserData : null);
		//
		//		$User->setData($User->user_id);
		//
		//
		//		if ($setCurrent == self::SET_CURRENT) {
		//			self::$current = $User;
		//		}
		//
		//		return $User;

		return static::$instances[$id] = $User;
	}

	public function authFromSession() {
		$session = (array)Session::i()[Session::USER];
		$this->deleteAuthInSession();

		$id = v(Data::USER_ID, false, $session);
		$hash = v('hashWithIP', false, $session);
		$ip = v('REMOTE_ADDR', false, $_SERVER);
		$curr_hash = sha1($id . '|' . $ip);

		if ($hash && $curr_hash && $hash == $curr_hash) $this->Data->authed($id);

		$this->saveAuthToSession();
	}

	public function deleteAuthInSession() {
		$params = [self::IS_LOGGED => false, 'hashWithIP' => false, Data::USER_ID => null];
		Session::i()[Session::USER] = $params;
	}

	protected function saveAuthToSession() {
		if (!$this->Data->user_id) {
			return;
		}

		$hashWithIP = sha1($this->Data->user_id . '|' . v('REMOTE_ADDR', false, $_SERVER));
		$params = [
			self::IS_LOGGED => true,
			Data::USER_ID   => $this->Data->user_id,
			'hashWithIP'    => $hashWithIP,
			self::REFRESHED => date('c')
		];
		Session::i()[Session::USER] = $params;
	}

	/**
	 * Set the Data parameter, which is initiated through User\Data::f()
	 *
	 * @param null|int $userId
	 *
	 * @return User
	 */
	private function setData($userId = null) {
		$this->Data = User\Data::f($userId);

		return $this;
	}

	public function __get($name) {
		if (isset($this->Data->$name)) return $this->Data->$name;

		throw new \Exception('Unknown property: ' . $name);
	}

	/**
	 * Export data as assoc array
	 *
	 * @return array
	 */
	public function exportData() {
		return [
			User\Data::EMAIL        => $this->Data->email,
			User\Data::USERNAME     => $this->Data->username,
			User\Data::DATE_CREATED => $this->Data->getDateCreated()
		];
	}

	/**
	 * Authenticate the User with data from $Data, and set it as current.
	 *
	 * @param array $data
	 *
	 * @return User
	 */
	public function auth(array $data) {
		$login = v('login', false, $data);
		$password = v(Data::PASSWORD, false, $data);
		if ($login && $password) $this->Data->authByPwd($login, $password);

		$this->saveAuthToSession();

		return $this;
	}

	/**
	 * Check if current User entity is logged
	 *
	 * @return boolean
	 */
	public function isLogged() {
		$U = (array)Session::i()[Session::USER];

		$sessionUserId = (bool)v(Data::USER_ID, false, $U);
		$isLogged = (bool)v(self::IS_LOGGED, false, $U);

		$result = $isLogged && ($sessionUserId == $this->user_id);

		return $result;
	}

}
