<?php

/**
 * Description of Auth
 *
 * @author Mukhenok
 */
namespace App\Flow\Block;
use \Core\Input;
use \Core\Output;
use \Core\User;
use \App\Block\Head;

class Auth extends \App\Flow {

	public function action_default() {
		$childFlow = $this->Input->get(Input\Http::INPUT_ROUTE)->get('step');
		$childFlow = $childFlow ? : 'reg';

		$this->runChildFlow($childFlow);
	}

	/**
	 *
	 * @return boolean
	 */
	public function action_login() {
		Head::addJsLink(Head::JS_BLOCK_AUTH);
		Head::addJsLink(Head::JS_BLOCK_AUTH_LOGIN);
		Head::addCssLink(Head::CSS_AUTH_LOGIN);

		if (!User::curr()->isLogged()) {
			\Core\User\Auth::getUser()->auth($this->Input->get(Input\Http::INPUT_POST));
		}

		$this->Output->bind('User', Auth::getUser());
	}

	public function action_profile() {
		$this->Output->bind('userData', User::curr()->exportData());
	}

	public function action_logout() {
		User::curr()->deleteAuth();

		$this->Output->header('Location: /');
	}

	public function action_reg() {
		Head::addJsLink(Www_Head::JS_BLOCK_AUTH);
		Head::addJsLink(Www_Head::JS_BLOCK_AUTH_LOGIN);

		$post = $this->Input->get(Input\Http::INPUT_POST);
		if (!$post->export()) {
			return null;
		}

		$Result = User::reg($post);

		if (!$Result->error && ($User = $Result->value) instanceof User && $User->isLogged()) {
			$redirect = ($this->Output instanceof Output\Http\Json ? '/json/block' : '') . '/auth/profile';
			$this->Output->header('Location: ' . $redirect);

			return true;
		}

		$this->Output->bind('data', $post);
	}

}