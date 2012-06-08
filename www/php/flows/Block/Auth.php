<?php

/**
 * Description of Auth
 *
 * @author Mukhenok
 */
class Flow_Block_Auth extends Flow_Block {

	public function callPre($action) {
		Block_Flow_Head::addJsLink('/js/block/auth.js');

		parent::callPre($action);
	}

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
		Block_Flow_Head::addJsLink('/js/block/auth/login.js');

		$this->Output->bind('User', Block_Auth::getUser());
	}

	public function action_reg() {
		
	}

}