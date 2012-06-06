<?php

/**
 * Description of Auth
 *
 * @author Mukhenok
 */
class Flow_Block_Auth extends Flow_Block {

	public function action_default() {
		$childFlow = $this->Input->get(Input_Http::INPUT_ROUTE)->get('step');
		$childFlow = $childFlow ? : 'reg';
		
		$this->runChildFlow($childFlow);
	}

	public function action_reg() {
		
	}

}