<?php

/**
 * Description of Auth
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
	 *
	 * @var Input
	 */
	protected $userAuth = null;

	/**
	 *
	 * @var Mapper_User_Auth
	 */
	protected $Mapper;

	protected function __construct() {
		$this->Mapper = new Mapper_User_Auth;
	}

	/**
	 *
	 * @param Input|int $userId
	 * @return User_Auth 
	 */
	public static function f() {
		$UserAuth = new self();

		$UserAuth->init();

		return $UserAuth;
	}

	protected function init() {

		$Data = $this->getEmptyAuth();

		$this->setAuth($Data);
	}

	/**
	 *
	 * @return \Input 
	 */
	protected function getEmptyAuth() {
		return new Input(array());
	}

	/**
	 *
	 * @param Input $Auth
	 * @return \User_Auth 
	 */
	protected function setAuth(Input $Auth) {
		$this->userAuth = $Auth;

		return $this;
	}

	/**
	 *
	 * @param string $login
	 * @param string $password
	 * @return \User_Auth 
	 */
	public function authByPwd($login, $password) {

		$hash = $this->buildHash($login, $password);

		$this->setAuth($this->Mapper->byHash($login, $hash));

		return $this;
	}

	/**
	 *
	 * @param string $login
	 * @param string $hash
	 * @return \User_Auth 
	 */
	public function authByHash($login, $hash) {

		$this->setAuth($this->Mapper->byHash($login, $hash));

		return $this;
	}

	/**
	 *
	 * @param string $field
	 * @return mixed
	 */
	protected function get($field) {
		return $this->userAuth->get($field);
	}

	/**
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
	 *
	 * @param string $login
	 * @param string $password
	 * @return string 
	 */
	private function buildHash($login, $password) {
		return sha1($login . ' / ' . $password);
	}

	/**
	 *
	 * @return int|null
	 */
	public function getUserId() {
		return $this->get(Mapper_User_Auth::F_USER_ID);
	}

	/**
	 *
	 * @return string|null
	 */
	public function getLogin() {
		return $this->get(Mapper_User_Auth::F_LOGIN);
	}

	/**
	 *
	 * @return string|null
	 */
	public function getHash() {
		return $this->get(Mapper_User_Auth::F_HASH);
	}

}