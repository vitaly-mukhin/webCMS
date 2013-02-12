<?php

/**
 * Description of Block\Flow_Profile
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block\Flow;

class Profile extends \App\Block\Flow {

	/**
	 * @param \Core\Input $InputRoute
	 *
	 * @return array
	 */
	protected function getRoute(\Core\Input $InputRoute) {
		return array('action' => 'auth', 'step' => 'profile');
	}

}
