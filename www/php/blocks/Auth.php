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
			$sessionId = Session::i()->getId();
			self::initUser($sessionId);
		}
	}

	/**
	 *
	 */
	private static function initUser() {
		$UserSessionData = Session::i()->getSection(Session::USER);

		self::$User = User::f($UserSessionData);
	}

	/**
	 *
	 * @return User
	 */
	public static function getUser() {
		return self::$User;
	}

}