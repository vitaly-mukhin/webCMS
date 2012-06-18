<?php

/**
 * Class, which should be included into every entity of User.
 * It contains auth info about user.
 *
 * @author Vitaliy_Mukhin
 */
class User_Auth {

	const USER_ID = 'user_id';
	const LOGIN = 'login';
	const PASSWORD = 'password';
	const HASH = 'hash';
	const PASSWORD_REPEAT = 'password_repeat';

	/**
	 * Storage, which contains data
	 *
	 * @var Input
	 */
	protected $userAuth = null;

	/**
	 * DataMapper which is responsible for operating with DB or any other storage
	 *
	 * @var Mapper_User_Auth
	 */
	protected $Mapper;

	protected function __construct() {
		$this->Mapper = new Mapper_User_Auth;
	}

	/**
	 * Factory method for creating new instance, and initiating it
	 *
	 * @return User_Auth 
	 */
	public static function f() {
		$UserAuth = new self();

		$UserAuth->init();

		return $UserAuth;
	}

	/**
	 * Check the data for creating a new N
	 *
	 * @param Input $Data
	 * 
	 * @return boolean
	 */
	public function checkReg(Input $Data) {
		$result = true;

		$result = $result && ($Data->get(User_Auth::LOGIN));

		$result = $result && ($Data->get(User_Auth::PASSWORD) == $Data->get(User_Auth::PASSWORD_REPEAT));

		$result = $result && $this->Mapper->checkReg($Data);

		return $result;
	}

	/**
	 * Initiating the storage with empty data 
	 */
	protected function init() {

		$Data = $this->getEmptyAuth();

		$this->setAuth($Data);
	}

	/**
	 * Returns empty data for initial
	 *
	 * @return Input 
	 */
	protected function getEmptyAuth() {
		return new Input(array());
	}

	/**
	 * 
	 *
	 * @param Input $Auth
	 * @return User_Auth 
	 */
	protected function setAuth(Input $Auth) {
		$this->userAuth = $Auth;

		return $this;
	}

	/**
	 * Authenticating user with login/password combination
	 *
	 * @param string $login
	 * @param string $password
	 * 
	 * @return User_Auth 
	 */
	public function authByPwd($login, $password) {

		$hash = $this->buildHash($login, $password);

		$this->setAuth($this->Mapper->byHash($login, $hash));

		return $this;
	}

	/**
	 * Authenticating user with login/hash combination
	 *
	 * @param string $login
	 * @param string $hash
	 * 
	 * @return User_Auth 
	 */
	public function authByHash($login, $hash) {

		$this->setAuth($this->Mapper->byHash($login, $hash));

		return $this;
	}

	/**
	 * Unified method for retrieving the data from storage
	 *
	 * @param string $field
	 * @return mixed
	 */
	protected function get($field) {
		return $this->userAuth->get($field);
	}

	/**
	 * Providing the registration
	 * 
	 * @todo Refactore the code: built-in the checkReg() inside this method
	 *
	 * @param int $user_id
	 * @param Input $Data
	 * @return int 
	 */
	public function reg($user_id, Input $Data) {
		$Data = new Input(array(
					Mapper_User_Auth::F_USER_ID => $user_id,
					Mapper_User_Auth::F_LOGIN => $Data->get(self::LOGIN),
					Mapper_User_Auth::F_HASH => $this->buildHash($Data->get(self::LOGIN), $Data->get(self::PASSWORD))
				));
		return $this->Mapper->reg($Data);
	}

	/**
	 * Build hash-string with login/password combination
	 *
	 * @param string $login
	 * @param string $password
	 * 
	 * @return string 
	 */
	private function buildHash($login, $password) {
		return sha1($login . ' / ' . $password);
	}

	/**
	 * Get the USER_ID
	 *
	 * @return int|null
	 */
	public function getUserId() {
		return $this->get(Mapper_User_Auth::F_USER_ID);
	}

	/**
	 * Get the LOGIN
	 *
	 * @return string|null
	 */
	public function getLogin() {
		return $this->get(Mapper_User_Auth::F_LOGIN);
	}

	/**
	 * Get the HASH
	 *
	 * @return string|null
	 */
	public function getHash() {
		return $this->get(Mapper_User_Auth::F_HASH);
	}

}