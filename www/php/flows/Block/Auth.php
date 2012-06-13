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

		if (!Block_Auth::getUser()->isLogged()) {
			Block_Auth::getUser()->auth($this->Input->get(Input_Http::INPUT_POST));
		}

		$this->Output->bind('User', Block_Auth::getUser());
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
//			var_dump($User);
		}
	}

}