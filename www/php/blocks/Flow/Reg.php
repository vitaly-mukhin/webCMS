<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block\Flow;
use App\Block\Flow;
use Core\Input;

class Reg extends Flow {

	/**
	 * @param \Core\Input $InputRoute
	 *
	 * @return array
	 */
	protected function getRoute(Input $InputRoute) {
		return array('action' => 'auth', 'step' => 'reg');
	}

}
