<?php

/**
 * Description of Userdata
 *
 * @author Vitaliy_Mukhin
 */
class User_Data {
	//

	const F_ID = 'id';
	const F_EMAIL = 'email';
	//
	const EMAIL = 'email';

	/**
	 *
	 * @var Input
	 */
	protected $userData = null;

	protected function __construct() {
		
	}

	/**
	 *
	 * @param Input|int $userId
	 * @return User_Data 
	 */
	public static function f($userId) {
		$UserData = new self();

		$Data = ($userId instanceof Input) ? $userId : $UserData->getUserData($userId);

		$UserData->init($Data);

		return $UserData;
	}

	/**
	 *
	 * @param int $userId
	 * @return \Input 
	 */
	protected function getUserData($userId) {
		return new Input(array(
					self::F_USERNAME => 'some_username',
					self::F_EMAIL => 'some@email.com',
					self::F_ID => $userId
				));
	}

	protected function init(Input $Data) {
		$this->userData = $Data;
	}
	
	public function reg(Input $Data) {
		
	}

}