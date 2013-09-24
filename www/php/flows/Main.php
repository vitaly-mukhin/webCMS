<?php

/**
 * Description of Flow_Www
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Flow;
use Core\Input as Input;
use Core\Output as Output;
use App\Block\Head as Head;
use App\Block\Auth as Auth;
use Core\Flow as Flow;
use App\Block\Nav as Nav;
use App\Block\Login as Login;

class Main extends \App\Flow {

	const IS_ROOT = true;

	const DEFAULT_ACTION = 'default';

	public function action_default() {
		$next = $this->Input->get(Input\Http::ROUTE)->get('page', 'index');
		$next = $next ? : 'index';
		$this->runChildFlow($next);
	}

	protected function callPre($action) {
		parent::callPre($action);

		Auth::process(array(), $this->Output);

		//		Login::process(array(), $this->Output);

		Head::addPageTitle('webCMS');

		Head::addJsLink(Head::JS_JQUERY);
		Head::addJsLink(Head::JS_BOOTSTRAP);
		Head::addJsLink(Head::JS_COMMON);

		Head::addCssLink(Head::CSS_BOOTSTRAP);
		Head::addCssLink(Head::CSS_MAIN);

		Login::process(array(), $this->Output);
	}

	protected function callPost($result) {
		Nav::process(array(), $this->Output);

		parent::callPost($result);
	}

}
