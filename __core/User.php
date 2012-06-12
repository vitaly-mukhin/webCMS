<?php

/**
 * Description of User
 *
 * @author Vitaliy_Mukhin
 * 
 */
class User {
	//

	const F_ID = 'id';
	//
	const IS_LOGGED = 'is_logged';
	const ID = 'id';
	const SET_CURRENT = true;

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

		$User->setAuth($UserData->get('user_id', -1));

		$User->setData($UserData->get('user_id', -1));

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
	 * @return User_Data
	 */
	private function getData() {
		return $this->Data;
	}

	/**
	 *
	 * @param Input|int $userId
	 * @return \User 
	 */
	private function setAuth($userId) {
		$this->Auth = User_Auth::f($userId);

		return $this;
	}

	/**
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
	 * @return User
	 */
	public static function reg(Input $Post) {
		$Data = new Input(array(
					User_Auth::LOGIN => $Post->get('login'),
					User_Auth::PASSWORD => $Post->get('password'),
					User_Auth::PASSWORD_REPEAT => $Post->get('password_repeat'),
					User_Data::EMAIL => $Post->get('email')
				));

		$User = User::f();

		if (!self::checkReg($Data)) {
			return null;
		}

		$User->getData()->reg($Data);

		return User::f(Session::i()->get(Session::USER));
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

		$data = array_merge(array(
			self::IS_LOGGED => self::NOT_LOGGED,
			self::ID => self::NO_ID
				), $data);

		return new Input($data);
	}

}