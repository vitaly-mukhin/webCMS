<?php

/**
 * Description of Flow_Block
 *
 * @author Vitaliy_Mukhin
 */
class Flow_AbstractBlock extends Flow {

	public function action_default() {
		$this->runChildFlow($this->Input->get(Input_Http::INPUT_ROUTE)->get(0));
	}

}