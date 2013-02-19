<?php

/**
 * Description of Auth
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block;
use Core\Block;
use Core\Input;
use Core\Session;
use Core\User;

class Auth extends Block {

	/**
	 *
	 * @var User
	 */
	private static $User;

	public static function process($params = array(), \Core\Output $Output = null) {
		if (empty(self::$User)) {
			self::initUser();
			if (!empty($_POST) && !self::$User->isLogged()) {
				self::$User->auth(new Input($_POST));
			}
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
