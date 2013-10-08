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
		$action = $this->Input->get(Input\Http::ROUTE)->get('action');

		if (!$action && User::curr()->isLogged()) {
			$action = 'profile';
		}

		$this->runChildFlow($action);
	}

	public function action_login() {
		$referrer = $this->Input->get(Input\Http::SERVER)->get('HTTP_REFERER', false);
		$currentDomain = $this->Input->get(Input\Http::SERVER)->get('SERVER_NAME');

		if (User::curr()->isLogged()) {
			if ($referrer && strpos($referrer, $currentDomain) !== false && !strpos($referrer, 'login')) $this->Output->header('Location: ' . $referrer);
			else  $this->Output->header('Location: /auth/profile');
		}

		Block\Head::addPageTitle('Привітаймося!');

		$this->Output->bind('', null);
	}

	public function action_logout() {
		Flow\Block\Logout::process([]);
	}

	public function action_reg() {
		Block\Auth\Reg::process(array(), $this->Output);
	}

	public function action_profile() {
		Block\Auth\Profile::process([], $this->Output);
	}

}
