<?php

/**
 * Description of Auth
 *
 * @author Vitaliy_Mukhin
 */
class Block_Auth extends Block {

	/**
	 *
	 * @var User
	 */
	private static $User;

	public static function process() {
		if (empty(self::$User)) {
			self::initUser();
		}
	}

	/**
	 *
	 */
	private static function initUser() {
		$UserSessionData = Session::i()->get(Session::USER);
		self::$User = User::f($UserSessionData, true);
	}

	/**
	 *
	 * @return User
	 */
	public static function getUser() {
		return self::$User;
	}

}