<?php

/**
 * Description of Flow_Block
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Block extends Flow {
	
	public function callPre($action) {
		Block_Auth::process();

		return parent::callPre($action);
	}

	public function action_default() {
		$this->runChildFlow($this->Input->get(Input_Http::INPUT_ROUTE)->get('action'));
	}

}