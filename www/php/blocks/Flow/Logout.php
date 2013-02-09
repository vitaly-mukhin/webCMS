<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block\Flow;

class Logout extends \App\Block\Flow {

	/**
	 * @param \Core\Input $InputRoute
	 *
	 * @return array
	 */
	protected function getRoute(\Core\Input $InputRoute) {
		return array('action' => 'auth', 'step' => 'logout');
	}

}
