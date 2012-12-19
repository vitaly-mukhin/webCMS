<?php

/**
 * Description of NoFlowFound
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Block_NoFlowFound extends Flow_AbstractBlock {

	public function action_default() {
		$this->Output->cookie('b404', $this->Input->get(Input\Http::INPUT_GET)->get(Dispatcher::ROUTE_IN_GET));
	}

}