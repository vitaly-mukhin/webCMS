<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block\Flow\Gallery;
use App\Block\Flow;
use Core\Input;

class Own extends Flow {

	/**
	 * @param \Core\Input $InputRoute
	 *
	 * @return array
	 */
	protected function getRoute(Input $InputRoute) {
		return array('action' => 'gallery', 'step' => 'own');
	}

}
