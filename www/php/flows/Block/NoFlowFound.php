<?php

/**
 * Description of NoFlowFound
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Block_NoFlowFound extends Flow {

	public function process() {
		return $this->redirect('default');
	}

	public function action_default() {
		$this->Output->header('HTTP/1.1 404 Not Found');
		$this->Output->cookie('b404', $this->Input->get(Input_Http::INPUT_GET)->get(Dispatcher::ROUTE_IN_GET));

		return true;
	}

}