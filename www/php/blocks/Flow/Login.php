<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block\Flow;
use App\Block\Flow;
use Core\Input;

class Login extends Flow {

	/**
	 * @param Input $InputRoute
	 * @return type 
	 */
	protected function getRoute(Input $InputRoute) {
		return array('action' => 'auth', 'step' => 'login');
	}

}