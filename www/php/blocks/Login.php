<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block;
use App\Block;
use Core\Input;

class Login extends \Core\Block {

	use \Core\Block\Flow;

	/**
	 * @param Input $InputRoute
	 *
	 * @return array
	 */
	protected function getRoute(Input $InputRoute) {
		return array('action' => 'auth', 'step' => 'login');
	}

}
