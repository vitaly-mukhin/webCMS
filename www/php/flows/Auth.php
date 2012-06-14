<?php

/**
 * Description of Auth
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Auth extends Flow {

	public function action_default() {
		$action = $this->Input->get(Input_Http::INPUT_ROUTE)->get('action');
		
		if (!$action && User::curr()->isLogged()) {
			$action = 'profile';
		}
		
		$this->runChildFlow($action);
	}

	public function action_login() {
		
	}

	public function action_reg() {
		Block_Flow_Reg::process(array(), $this->Output);
	}
	
	public function action_profile() {
		Block_Flow_Profile::process(array(), $this->Output);
	}

}