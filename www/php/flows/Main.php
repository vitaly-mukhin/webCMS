<?php

/**
 * Description of Flow_Www
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Flow;
use Core\Input;
use Core\Output;
use App\Block\Head;
use App\Block\Auth;
use Core\Flow;
use App\Block\Nav;

class Main extends \App\Flow {

	const IS_ROOT        = true;
	const DEFAULT_ACTION = 'default';

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

		//		Block\Login::process(array(), $this->Output);
	}

	protected function callPost($result) {
		Nav::process(array(), $this->Output);

		parent::callPost($result);
	}

	public function action_default() {
		$next = $this->Input->get(Input\Http::INPUT_ROUTE)->get('page', 'index');
		$next = $next ? : 'index';
		$this->runChildFlow($next);
	}

}
