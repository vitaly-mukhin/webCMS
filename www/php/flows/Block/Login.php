<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Block_Login extends Flow_AbstractBlock {

	/**
	 *
	 * @return boolean 
	 */
	public function action_default() {
		$this->Output->bind('User', Block_Auth::getUser());
	}

}