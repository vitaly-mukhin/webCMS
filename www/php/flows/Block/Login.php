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
		$step = $this->Input->get(Input_Http::INPUT_ROUTE)->get('step');
		$step = $step ? : 'check';

		$this->runChildFlow($step);
	}

	public function action_check() {
		
	}

}