<?php

/**
 * Description of Auth
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block;
use Core\Block;
use Core\Session;
use Core\User;

class Auth extends Block {

	/**
	 *
	 * @var User
	 */
	private static $User;

	public static function process($params = array()) {
		if (empty(self::$User)) {
			self::initUser();
		}
	}

	/**
	 *
	 */
	private static function initUser() {
		$UserSessionData = Session::i()->get(Session::USER);
		self::$User      = User::f($UserSessionData, true);
	}

	/**
	 *
	 * @return User
	 */
	public static function getUser() {
		return self::$User;
	}

}