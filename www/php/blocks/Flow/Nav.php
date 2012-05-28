<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
class Block_Flow_Nav extends Block_Flow {

	/**
	 *
	 * @return type 
	 */
	protected function getRoute(Input $InputRoute) {
		return array('action' => 'nav');
	}

}