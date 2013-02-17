<?php

/**
 * Description of Auth
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Flow;
use App\Block;
use App\Flow;
use Core\Input;
use Core\User;

class Auth extends Flow {

	public function action_default() {
		$action = $this->Input->get(Input\Http::INPUT_ROUTE)->get('action');

		if (!$action && User::curr()->isLogged()) {
			$action = 'profile';
		}

		$this->runChildFlow($action);
	}

	public function action_login() {
//		Block\Login::process(array(), $this->Output);

		$referrer      = $this->Input->get(Input\Http::INPUT_SERVER)->get('HTTP_REFERRER', false);
		$currentDomain = $this->Input->get(Input\Http::INPUT_SERVER)->get('SERVER_NAME');

		if (User::curr()->isLogged() && $referrer && strpos($referrer, $currentDomain) !== false) {
			$this->Output->header('Location: ' . $referrer);
		}

		Block\Head::addPageTitle('Привітаймося!');

		$this->Output->bind('', null);
	}

	public function action_logout() {
		Block\Logout::process(array());
	}

	public function action_reg() {
		Block\Reg::process(array());
	}

	public function action_profile() {
		Block\Flow\Profile::process(array(), $this->Output);
	}

}
