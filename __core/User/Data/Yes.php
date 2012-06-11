<?php

/**
 * Description of User_Data_Yes
 *
 * @author Vitaliy_Mukhin
 */
class User_Data_Yes extends User_Data {

	protected function setData($id) {
		$this->userData = new Input(array(
					self::F_ID => $id,
					self::F_USERNAME => Session::i()->get(Session::USER)->get(self::F_USERNAME),
					self::F_EMAIL => Session::i()->get(Session::USER)->get(self::F_EMAIL)
				));

		return $this;
	}

}