<?php

class Flow_Block_Gallery extends Flow_AbstractBlock {

	/**
	 *
	 * @return boolean 
	 */
	public function action_default() {
		$this->runChildFlow($this->Input->get(Input_Http::INPUT_ROUTE)->get('step'));
	}
    
    public function action_menu() {
        $this->Output->bind('menuArray', array(
			'Останні' => '/gallery',
			'Додати' => '/gallery/add'
		));
    }
    
    public function action_own() {
        $this->Output->bind('albumArray', Album_Mapper::getOwnN(Album_Mapper::LATEST_N));
    }

}