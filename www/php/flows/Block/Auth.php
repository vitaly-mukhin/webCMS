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
		Block_Head::addJsLink(Block_Head::JS_BLOCK_AUTH);
		Block_Head::addJsLink(Block_Head::JS_BLOCK_AUTH_LOGIN);
		Block_Head::addCssLink(Block_Head::CSS_AUTH_LOGIN);

		if (!User::curr()->isLogged()) {
			Block_Auth::getUser()->auth($this->Input->get(Input_Http::INPUT_POST));
		}

		$this->Output->bind('User', Block_Auth::getUser());
		
		return true;
	}

	public function action_profile() {
		$this->Output->bind('userData', User::curr()->exportData());
	}

	public function action_logout() {
		User::curr()->deleteAuth();

		$this->Output->header('Location: /');
	}

	public function action_reg() {
		Block_Head::addJsLink(Block_Head::JS_BLOCK_AUTH);
		Block_Head::addJsLink(Block_Head::JS_BLOCK_AUTH_LOGIN);

		$post = $this->Input->get(Input_Http::INPUT_POST);
		if (!$post->export()) {
			return null;
		}

		$Result = User::reg($post);

		if (!$Result->error && ($User = $Result->value) instanceof User && $User->isLogged()) {
			$this->Output->header('Location: ' . ($this->Output instanceof Output_Http_Json ? '/json/block' : '') . '/auth/profile');
			return true;
		}

		$this->Output->bind('data', $post);
	}

}