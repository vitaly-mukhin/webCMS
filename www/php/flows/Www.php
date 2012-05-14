<?php

/**
 * Description of Flow_Www
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Www extends Flow {

	const DEFAULT_ACTION = 'default';

	protected function callPre($action) {
		parent::callPre($action);

		Block_Login::process($this->Output);
	}

	protected function callPost($result) {
		Block_Head::process($this->Output);

		return parent::callPost($result);
	}

	public function action_default() {
		$next = $this->Input->get(Input_Http::INPUT_ROUTE)->get('page', 'index');
		$next = ($next) ? $next : 'index';
		return $next;
	}

}