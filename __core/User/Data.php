<?php

/**
 * Description of Userdata
 *
 * @author Vitaliy_Mukhin
 */
abstract class User_Data extends User {
	
	const F_USERNAME = 'username';
	const F_EMAIL = 'email';
	const F_ID = 'id';
	
	/**
	 *
	 * @var Input
	 */
	protected $userData = null;
	
	public function __construct(User $User, $id) {
		parent::__construct($User);
		
		$this->setData($id);
	}
	
	abstract protected function setData($id);

	/**
	 *
	 * @return Input
	 */
	public function getData() {
		return $this->userData;
	}
}