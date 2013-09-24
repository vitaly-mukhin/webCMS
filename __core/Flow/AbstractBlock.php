<?php

/**
 * Description of Flow_Block
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\Flow;
use Core\Input\Http;
use Core\Flow;

class AbstractBlock extends Flow {

	public function action_default() {
		$this->runChildFlow($this->Input->get(Http::ROUTE)->get(0));
	}

}
