<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
class Block_Flow_Logout extends Block_Flow {

	/**
	 * @param Input $InputRoute
	 * @return type 
	 */
	protected function getRoute(Input $InputRoute) {
		return array('action' => 'auth', 'step' => 'logout');
	}

}