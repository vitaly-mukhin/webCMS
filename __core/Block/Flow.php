<?php

/**
 * Description of Trait \Core\Block\Flow
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\Block;
use App\Block\Auth\Reg;
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
		$getData = $InputDefault->get(Input\Http::GET)->export();
		$routeData = $this->getRoute($InputDefault->get(Input\Http::GET));
		$params = array_merge($getData, $this->params);
		$InputGET = new Input($params);

		$this->InputHttp = new Input\Http(array(
		                                       Input\Http::ROUTE  => new Input($routeData),
		                                       Input\Http::GET    => $InputGET,
		                                       Input\Http::POST   => $InputDefault->get(Input\Http::POST),
		                                       Input\Http::SERVER => $InputDefault->get(Input\Http::SERVER),
		                                       Input\Http::COOKIE => $InputDefault->get(Input\Http::COOKIE)
		                                  ));
	}

}
