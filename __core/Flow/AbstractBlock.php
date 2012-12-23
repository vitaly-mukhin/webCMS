<?php

/**
 * Description of Flow_Block
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;
use Input\Http;

class Flow_AbstractBlock extends Flow {

	public function action_default() {
		$this->runChildFlow($this->Input->get(Input\Http::INPUT_ROUTE)->get(0));
	}

}