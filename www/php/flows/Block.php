<?php

/**
 * Description of Flow_Block
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Block extends Flow {

	public function action_default() {
		$next = $this->Input->get(Input_Http::INPUT_ROUTE)->get(0);
		return $next;
	}

}