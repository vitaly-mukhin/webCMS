<?php

/**
 * Description of Flow_Www
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Www extends Flow {

	public function process() {
		Block_Login::process($this->Output);

		$next = $this->Input->get(Input_Http::INPUT_ROUTE)->get('page', 'index');
		$next = ($next) ? $next : 'index';
		return $this->redirect($next);
	}

}