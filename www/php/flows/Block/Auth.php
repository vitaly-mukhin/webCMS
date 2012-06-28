<?php

/**
 * Description of Auth
 *
 * @author Mukhenok
 */
class Flow_Block_Auth extends Flow_Block {

	public function action_default() {
		$childFlow = $this->Input->get(Input_Http::INPUT_ROUTE)->get('step');
		$childFlow = $childFlow ? : 'reg';

		$this->runChildFlow($childFlow);
	}

	/**
	 *
	 * @return boolean 
	 */
	public function action_login() {
		Block_Flow_Head::addJsLink('/js/block/auth.js');
		Block_Flow_Head::addJsLink('/js/block/auth/login.js');

		if (!User::curr()->isLogged()) {
			Block_Auth::getUser()->auth($this->Input->get(Input_Http::INPUT_POST));
		}

		$this->Output->bind('User', Block_Auth::getUser());
	}
	
	public function action_profile() {
		$this->Output->bind('userData', User::curr()->exportData());
	}
	
	public function action_logout() {
		User::curr()->deleteAuth();
		
		$this->Output->header('Location: /');
	}

	public function action_reg() {
		Block_Flow_Head::addJsLink('/js/block/auth.js');
		Block_Flow_Head::addJsLink('/js/block/auth/reg.js');

		$post = $this->Input->get(Input_Http::INPUT_POST);

		if (!$post->export()) {
			return null;
		}

		$User = User::reg($post);

		if ($User && $User->isLogged()) {
			$this->Output->header('Location: /block/auth');
		}
	}

}