<?php

/**
 * Description of Logged
 *
 * @author Vitaliy_Mukhin
 */
class User_Logged extends User {

	public function __construct($User, $id) {
		$this->id = $id;

		parent::__construct($User);
	}

	private function onDecorate() {
		$this->data = new Input(array(
					self::USERNAME => 'gogol-mogol'
				));
	}

	public function isLogged() {
		return true;
	}

	public function getId() {
		return $this->id;
	}

}