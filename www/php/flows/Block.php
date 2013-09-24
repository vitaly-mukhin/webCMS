<?php

/**
 * Description of Flow_Block
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Flow;
use Core\Flow\AbstractBlock;
use Core\Input\Http;

class Block extends AbstractBlock {

	public function callPre($action) {
		\App\Block\Auth::process();

		parent::callPre($action);
	}

	public function action_default() {
		$this->runChildFlow($this->Input->get(Http::ROUTE)->get('action'));
	}

}
