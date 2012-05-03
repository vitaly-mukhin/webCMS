<?php

/**
 * Description of www
 *
 * @author Mukhenok
 */
class Flow_Www_NoFlowFound extends Flow {

	public function process() {
		return $this->redirect('default');
	}
	
	public function action_default() {
		$this->Output->bind('result', 'flow not found');
		
		return true;
	}

}