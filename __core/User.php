<?php

/**
 * Description of User
 *
 * @author Vitaliy_Mukhin
 * 
 * @method boolean isLogged
 * @method Input getData
 */
class User {

	const LOGGED = true;
	const NOT_LOGGED = false;
	const IS_LOGGED = 'is_logged';
	const ID = 'id';
	const NO_ID = null;
	const USERNAME = 'username';
	const EMPTY_USERNAME = '';

	/**
	 *
	 * @var User
	 */
	protected $original;

	public function __construct(User $User = null) {
		$this->original = $User;
	}

	/**
	 *
	 * @param string $method
	 * @param mixed $arguments
	 * @return null|mixed 
	 */
	public function __call($method, $arguments) {
		if ($this->original && $this->original instanceof User) {
			return call_user_func_array(array($this->original, $method), $arguments);
		}

		return null;
	}

	/**
	 *
	 * @param Input $SessionData
	 * @return \User
	 */
	public static function f(Input $SessionData = null) {
		$SessionData = self::getFullInput($SessionData);

		$User = new User();

		// Logged
		$User = ($SessionData->get(self::IS_LOGGED) == self::LOGGED) ? new User_Logged_Yes($User) : new User_Logged_No($User);
		$userId = $SessionData->get(self::ID);
		var_dump('before '. $userId);

		// Userdata
		$userId = $SessionData->get(self::ID);
		var_dump('after '. $userId);
		$User = ($userId == self::NO_ID) ? new User_Data_No($User, null) : new User_Data_Yes($User, $userId);

		return $User;
	}
	
	/**
	 * Create a user account, and init it
	 *
	 * @param Input $Post data for user account
	 * 
	 * @return User
	 */
	public static function reg(Input $Post) {
		$data = array(
			self::IS_LOGGED => self::LOGGED,
			self::ID => $Post->get('id', rand(100, 999)),
			self::USERNAME => $Post->get('login', 'Abra-kadabra')
		);
		
		Session::i()->set(Session::USER, $data);
		
		return User::f(Session::i()->get(Session::USER));
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

		// TODO: for tests
//		$data = array(
//			self::IS_LOGGED => self::LOGGED,
//			self::ID => 1
//		);

		return new Input($data);
	}

}