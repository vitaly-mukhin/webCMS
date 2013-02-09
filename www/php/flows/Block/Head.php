<?php

/**
 * Description of Head
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Block\Head extends Flow_AbstractBlock {

	public
	function action_default() {
		$this->Output->bind('pageTitle', Block\Head::getPageTitle());
		$this->Output->bind('jsLinks', Block\Head::getJsLinks());
		$this->Output->bind('cssLinks', Block\Head::getCssLinks());
	}

}
