<?php

/**
 * Description of Reg
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block\Auth;
use App\Block;
use Core\Block\Flow;
use Core\Input;

class Reg extends \Core\Block {

	use Flow;

	/**
	 * @param \Core\Input $InputRoute
	 *
	 * @return array
	 */
	protected function getRoute(Input $InputRoute) {
		return array('action' => 'auth', 'step' => 'reg');
	}

}
