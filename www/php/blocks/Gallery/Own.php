<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block\Gallery;
use Core\Input;

class Own extends \Core\Block {

	/**
	 * @param \Core\Input $InputRoute
	 *
	 * @return array
	 */
	protected function getRoute(Input $InputRoute) {
		return array('action' => 'gallery', 'step' => 'own');
	}

}
