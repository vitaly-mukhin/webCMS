<?php

namespace App\Flow\Block;
use Core\Input;

class Gallery extends \App\Flow\Block {

	/**
	 *
	 * @return boolean
	 */
	public function action_default() {
		$this->runChildFlow($this->Input->get(Input\Http::INPUT_ROUTE)->get('step'));
	}

	public function action_menu() {
		$this->Output->bind('menuArray', array('Останні' => '/gallery',
		                                       'Додати'  => '/gallery/add'));
	}

	public function action_own() {
		$this->Output->bind('albumArray', Album_Mapper::getOwnN(Album_Mapper::LATEST_N));
	}

}
