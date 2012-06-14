<?php

/**
 * Description of Block_Flow_Profile
 *
 * @author Vitaliy_Mukhin
 */
class Block_Flow_Profile extends Block_Flow {

	/**
	 * @param Input $InputRoute
	 * @return type 
	 */
	protected function getRoute(Input $InputRoute) {
		return array('action' => 'auth', 'step' => 'profile');
	}

}