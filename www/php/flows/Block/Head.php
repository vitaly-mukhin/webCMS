<?php

/**
 * Description of Head
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Block_Head extends Flow_AbstractBlock {

	public function action_default() {
		$this->Output->bind('pageTitle', Block_Flow_Head::getPageTitle());
		$this->Output->bind('jsLinks', Block_Flow_Head::getJsLinks());
		$this->Output->bind('cssLinks', Block_Flow_Head::getCssLinks());
	}

}