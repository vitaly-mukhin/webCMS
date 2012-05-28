<?php

/**
 * Description of Abstract
 *
 * @author Vitaliy_Mukhin
 */
abstract class Block_Flow extends Block {

	/**
	 *
	 * @var Dispatcher
	 */
	protected $Dispatcher;

	/**
	 *
	 * @var Input_Http
	 */
	protected $InputHttp;

	const INITIAL_FLOW_STRING = 'block';

	/**
	 *
	 * @param array $params 
	 */
	protected function init($params) {
		parent::init($params);

		$this->initInputs();

		$this->Dispatcher = Dispatcher::di(array(
					Dispatcher::PARAM_INITIAL_FLOW => self::INITIAL_FLOW_STRING
				));

		return $this;
	}

	/**
	 *
	 * @param array $params 
	 */
	protected function initInputs() {
		$InputDefault = Input_Http::getDefault();

		// generating _GET array with combined values
		$getData = $InputDefault->get(Input_Http::INPUT_GET)->export();
		$routeData = $this->getRoute($InputDefault->get(Input_Http::INPUT_GET));
		$params = array_merge($getData, $this->params);
		$InputGET = new Input($params);

		$this->InputHttp = new Input_Http(array(
					Input_Http::INPUT_ROUTE => new Input($routeData),
					Input_Http::INPUT_GET => $InputGET,
					Input_Http::INPUT_POST => $_POST,
					Input_Http::INPUT_COOKIE => $_COOKIE
				));
	}

	/**
	 * @param Input $InputRoute
	 * @return array 
	 */
	abstract protected function getRoute(Input $InputRoute);

	/**
	 *
	 * @return string
	 */
	final protected function invoke() {
		return $this->Dispatcher->flow($this->InputHttp);
	}

	/**
	 *
	 * @param Output $Output
	 * @param array $params 
	 */
	public static function process($params, Output $Output) {
		$Block = new static();

		$Block->init($params);

		$result = $Block->invoke();

		$Output->bind(get_called_class(), $result);
	}

}