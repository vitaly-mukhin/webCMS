<?php

/**
 * Description of NoFlowFound
 *
 * @author Vitaliy_Mukhin
 */
class Flow_NoFlowFound extends Flow {

	public function action_default() {
		$this->Output->header('HTTP/1.1 404 Not Found');
		$this->Output->cookie('p404', $this->Input->get(Input_Http::INPUT_GET)->get(Dispatcher::ROUTE_IN_GET));

		$this->Output->bind('result', 'no flow found');

		return true;
	}

}