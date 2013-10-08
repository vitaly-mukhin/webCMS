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
			$User = self::$User;
			if (!$User->isLogged() && !empty($_POST['auth'])) {
				$User->auth($_POST);
			}
		}
	}

	/**
	 *
	 */
	private static function initUser() {
		self::$User = User::curr();
		self::$User->authFromSession();
	}

	/**
	 *
	 * @return User
	 */
	public static function getUser() {
		return self::$User;
	}

}
