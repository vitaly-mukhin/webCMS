<?php

/**
 * Description of Trait \Core\Block\Flow
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\Block;
use Core\Block;
use Core\Dispatcher;
use Core\Input;
use Core\Input\Http;
use Core\Output;
use Core\Renderer;

trait Flow {

	/**
	 *
	 * @var Dispatcher
	 */
	protected $Dispatcher;

	/**
	 *
	 * @var Input\Http
	 */
	protected $InputHttp;

	/**
	 * @var string
	 */
	protected $initialFlowString = 'block';

	/**
	 * @return \Core\Output\Http
	 */
	protected function invoke() {
		$Output = new Output\Http;

		$this->Dispatcher->flow($this->InputHttp, $Output);

		return $Output;
	}

	/**
	 *
	 * @param array $params
	 *
	 * @return Block|Block\Flow
	 */
	protected function init($params) {
		parent::init($params);

		$this->initInputs();

		$this->Dispatcher = Dispatcher::di(array(Dispatcher::PARAM_INITIAL_FLOW => $this->initialFlowString));

		return $this;
	}

	/**
	 *
	 * @return void
	 */
	protected function initInputs() {
		$InputDefault = Input\Http::getDefault();

		// generating _GET array with combined values
		$getData   = $InputDefault->get(Input\Http::INPUT_GET)->export();
		$routeData = $this->getRoute($InputDefault->get(Input\Http::INPUT_GET));
		$params    = array_merge($getData, $this->params);
		$InputGET  = new Input($params);

		$this->InputHttp = new Input\Http(array(Input\Http::INPUT_ROUTE  => new Input($routeData),
		                                        Input\Http::INPUT_GET    => $InputGET,
		                                        Input\Http::INPUT_POST   => $InputDefault->get(Input\Http::INPUT_POST),
		                                        Input\Http::INPUT_SERVER => $InputDefault->get(Input\Http::INPUT_SERVER),
		                                        Input\Http::INPUT_COOKIE => $InputDefault->get(Input\Http::INPUT_COOKIE)));
	}

}
