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
		
		Block_Head::addPageTitle('webCMS');
		
		Block_Head::addJsLink('js/jquery-1.7.2.min.js');
		Block_Head::addJsLink('js/bootstrap-2.0.3.js');
		Block_Head::addCssLink('css/bootstrap-2.0.3.css');
		
		Block_Head::addJsLink('js/test.js');
		Block_Head::addCssLink('css/main.css');

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