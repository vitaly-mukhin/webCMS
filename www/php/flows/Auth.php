<?php

/**
 * Description of Auth
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Flow;
use App\Block;

class Auth extends \App\Flow {

	public function action_default() {
		$action = $this->Input->get(\Core\Input\Http::INPUT_ROUTE)->get('action');

		if (!$action && \Core\User::curr()->isLogged()) {
			$action = 'profile';
		}

		return $this->runChildFlow($action);
	}

	public function action_login() {
		Block\Flow\Login::process(array(), $this->Output);

		$referer       = $this->Input->get(\Core\Input\Http::INPUT_SERVER)->get('HTTP_REFERER', false);
		$currentDomain = $this->Input->get(\Core\Input\Http::INPUT_SERVER)->get('SERVER_NAME');

		if (\Core\User::curr()->isLogged() && $referer && strpos($referer, $currentDomain) !== false) {
			$this->Output->header('Location: ' . $referer);
		}

		Block\Head::addPageTitle('Привітаймося!');

		$this->Output->bind('', null);

		return true;
	}

	public function action_logout() {
		Block\Flow\Logout::process(array(), $this->Output);
	}

	public function action_reg() {
		Block\Flow\Reg::process(array(), $this->Output);
	}

	public function action_profile() {
		Block\Flow\Profile::process(array(), $this->Output);
	}

}