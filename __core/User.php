<?php

/**
 * Description of User
 *
 * @author Vitaliy_Mukhin
 * 
 */
class User {

	const ID = 'id';
	const SET_CURRENT = true;
	const IS_LOGGED = 'is_logged';

	/**
	 *
	 * @var User
	 */
	protected static $current;

	/**
	 *
	 * @var User_Data
	 */
	protected $Data;

	/**
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
	 * @return \User
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
	 *
	 * @param Input|int $userId
	 * @return \User 
	 */
	private function setData($userId) {
		$this->Data = User_Data::f($userId);

		return $this;
	}

	/**
	 *
	 * @param Input|int $userId
	 * @return \User 
	 */
	private function setAuth(Input $Data = null) {
		$this->Auth = User_Auth::f();

		Session::i()->set(Session::USER, array(
			'is_logged' => false
		));

		if (is_null($Data)) {
			return $this;
		}

		if (($login = $Data->get(User_Auth::LOGIN)) && ($password = $Data->get(User_Auth::PASSWORD))) {
			$this->Auth->authByPwd($login, $password);
		} elseif (($login = $Data->get(User_Auth::LOGIN)) && ($hash = $Data->get(User_Auth::HASH))) {
			$this->Auth->authByHash($login, $hash);
		}

		if ($this->Auth->getUserId()) {
			Session::i()->set(Session::USER, array(
				'is_logged' => true,
				'login' => $this->Auth->getLogin(),
				'hash' => $this->Auth->getHash(),
				'refreshed' => date('c')
			));
		}

		return $this;
	}

	/**
	 *
	 * @return User|null 
	 */
	public static function curr() {
		return empty(self::$current) ? null : self::$current;
	}
	
	public function exportData() {
		return array(
			User_Data::EMAIL => $this->Data->getEmail(),
			User_Data::USERNAME => $this->Data->getUsername()
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

		$user_id = $User->Data->reg($Data);

		if (!$user_id) {
			return null;
		} else {
			$User->setData($user_id);
		}

		$auth_id = $User->Auth->reg($user_id, $Data);

		if (!$auth_id) {
			return null;
		} else {
			$User->setAuth($Data);
		}

		return $User;
	}

	/**
	 *
	 * @param Input $Post
	 * @return boolean 
	 */
	private static function checkReg(Input $Post) {
		$result = true;

		$result = $result && ($Post->get(User_Auth::LOGIN));
		$result = $result && ($Post->get(User_Auth::PASSWORD) == $Post->get(User_Auth::PASSWORD_REPEAT));
		$result = $result && filter_var($Post->get(User_Data::EMAIL), FILTER_SANITIZE_EMAIL);

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
	 *
	 * @param Input $Data
	 * @return User 
	 */
	public function auth(Input $Data) {
		return self::f($Data, self::SET_CURRENT);
	}

	/**
	 *
	 * @return boolean
	 */
	public function isLogged() {
		return (bool)Session::i()->get(Session::USER)->get(self::IS_LOGGED);
	}
	
	public function getUsername() {
		return $this->Data->getUsername();
	}

}