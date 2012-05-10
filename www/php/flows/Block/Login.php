<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Block_Login extends Flow {

	/**
	 *
	 * @return mixed
	 */
	public function process() {
		return $this->redirect('default');
	}

	/**
	 *
	 * @return boolean 
	 */
	public function action_default() {
		return true;
	}

}