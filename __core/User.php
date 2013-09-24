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

	private function __construct() {
		$this->Data = User\Data::f();
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
	 * @return \Core\Result
	 */
	public static function reg(Input $Post) {
		$User = static::f();

		// add user to users table
		$Result = $User->Data->reg(v([User\Data::EMAIL, User\Data::USERNAME, User\Data::PASSWORD, User\Data::PASSWORD_REPEAT], '', $Post->export()));
		if ($Result->error) return $Result;

		$User = static::f($Result->value);

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

		$User->setData($User->user_id);


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
	 * @return Input
	 */
	private static function getFullInput(Input $Input = null) {
		return $Input ? : new Input();
	}

	/**
	 * Set the Auth parameter, which is initiated through User\Data::f()
	 *
	 * @param Input $Data
	 *
	 * @return User
	 */
	private function setAuth(Input $Data = null) {
		$this->deleteAuthInSession();

		if (is_null($Data)) return $this;

		$login = $Data->get(Data::EMAIL);
		$password = $Data->get(Data::PASSWORD);
		if ($login && $password) $this->Data->authByPwd($login, $password);

		if ($this->Data->user_id) $this->saveAuth();

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
		                       Data::USER_ID   => $this->Data->user_id,
		                       Data::EMAIL     => $this->Data->login,
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

	public static function i($id = null) {
		if (isset(static::$instances[$id])) return static::$instances[$id];

		/* @var \Core\User $User */
		$User = new static();
		if ($id) $User->setData($id);

		return static::$instances[$id] = $User;
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
		return array(
			User\Data::EMAIL        => $this->Data->email,
			User\Data::USERNAME     => $this->Data->username,
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
		return static::f($Data, static::SET_CURRENT);
	}

	/**
	 * Check if current User entity is logged
	 *
	 * @return boolean
	 */
	public function isLogged() {
		$U = new Input(Session::i()->get(Session::USER));

		$sessionUserId = $U ? (bool)$U->get(Data::USER_ID) : false;
		$isLogged = $U && $U->get(self::IS_LOGGED);

		$result = $isLogged && ($sessionUserId == $this->user_id);

		return $result;
	}

	/**
	 * Authenticating user with login/password combination
	 *
	 * @param string $email
	 * @param string $password
	 *
	 * @return static
	 */
	public function authByPwd($email, $password) {
		$this->Data->authByPwd($email, $password);

		return $this;
	}

	/**
	 * Authenticating user with login/hash combination
	 *
	 * @param string $email
	 * @param string $hash
	 *
	 * @return static
	 */
	public function authByHash($email, $hash) {
		$this->Data->authByHash($email, $hash);

		return $this;
	}

}
