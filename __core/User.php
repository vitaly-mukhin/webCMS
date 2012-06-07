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
	 * @param Input $Input
	 * @return \User
	 */
	public static function f(Input $Input = null) {
		$Input = self::getFullInput($Input);

		$User = new User();

		// Logged
		$User = ($Input->get(self::IS_LOGGED) == self::LOGGED) ? new User_Logged_Yes($User) : new User_Logged_No($User);

		// Userdata
		$userId = $Input->get(self::ID);
		$User = ($userId == self::NO_ID) ? new User_Data_No($User, null) : new User_Data_Yes($User, $userId);

		return $User;
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