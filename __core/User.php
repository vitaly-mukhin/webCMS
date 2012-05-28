<?php

/**
 * Description of User
 *
 * @author Vitaliy_Mukhin
 */
class User {

	const LOGGED = true;
	const NOT_LOGGED = false;
	const IS_LOGGED = 'is_logged';
	const ID = 'id';
	const USERNAME = 'username';

	/**
	 *
	 * @var User
	 */
	protected $original;

	public function __construct(User $User = null) {
		$this->original = $User;

		$this->onDecorate();
	}

	public function __call($method, $arguments) {
		if ($this->original && $this->original instanceof User) {
			return call_user_func_array(array($this->original, $method), $arguments);
		}

		return null;
	}

	public function isLogged() {
		return false;
	}

	private function onDecorate() {
		$this->data = new Input(array());
	}

	public function getUsername() {
		return $this->data->get(self::USERNAME, '');
	}

	/**
	 *
	 * @param Input $Input
	 * @return \User
	 */
	public static function f($Input) {
		$User = new User();

		// Logged
		if ($Input->get(self::IS_LOGGED, self::NOT_LOGGED) && $Input->get(self::ID, null)) {
			$User = new User_Logged($User, $Input->get(self::ID, null));
		}

		return $User;
	}

}