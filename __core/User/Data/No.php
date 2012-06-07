<?php

/**
 * Description of No
 *
 * @author Vitaliy_Mukhin
 */
class User_Data_No extends User_Data {

	protected function setData($id) {
		$this->userData = new Input(array());

		return $this;
	}

}