<?php

/**
 * Description of User_Data_Yes
 *
 * @author Vitaliy_Mukhin
 */
class User_Data_Yes extends User_Data {

	protected function setData($id) {
		$this->userData = new Input(array(
					self::F_ID => 100500,
					self::F_USERNAME => 'Slevin',
					self::F_EMAIL => 'email@domain.com',
				));

		return $this;
	}

}