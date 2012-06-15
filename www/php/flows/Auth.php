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
		Block_Flow_Login::process(array(), $this->Output);
		
		$referer = $this->Input->get(Input_Http::INPUT_SERVER)->get('HTTP_REFERER', false);
		$currentDomain = $this->Input->get(Input_Http::INPUT_SERVER)->get('SERVER_NAME');
		
		if (User::curr()->isLogged() && $referer && strpos($referer, $currentDomain) !== false) {
			$this->Output->header('Location: '.$referer);
		}
	}

	public function action_logout() {
		Block_Flow_Logout::process(array(), $this->Output);
	}

	public function action_reg() {
		Block_Flow_Reg::process(array(), $this->Output);
	}
	
	public function action_profile() {
		Block_Flow_Profile::process(array(), $this->Output);
	}

}