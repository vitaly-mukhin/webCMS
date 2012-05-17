<?php

/**
 * Description of Index
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Index extends Flow {

	public function action_default() {
		Block_Head::addPageTitle('index');

		$this->Output->bind('result', 'OK');
	}

}