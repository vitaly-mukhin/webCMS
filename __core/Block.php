<?php

/**
 * Description of Block
 *
 * @author Vitaliy_Mukhin
 */
abstract class Block {

	/**
	 *
	 * @var mixed
	 */
	protected $params;

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
	public function init($params) {
		$this->initInputs($params);
		$this->Dispatcher = new Dispatcher();
		$this->Dispatcher->setInitialFlow(self::INITIAL_FLOW_STRING);
	}

	/**
	 *
	 * @param array $params 
	 */
	protected function initInputs($params) {
		$InputDefault = Input_Http::getDefault();

		// generating _GET array with combined values
		$getData = $InputDefault->get(Input_Http::INPUT_GET)->export();
		$params = array_merge($getData, $params);
		$InputGET = new Input($params);

		$this->InputHttp = new Input_Http(array(
					Input_Http::INPUT_ROUTE => new Input($this->getRoute()),
					Input_Http::INPUT_GET => $InputGET,
					Input_Http::INPUT_POST => $_POST,
					Input_Http::INPUT_COOKIE => $_COOKIE
				));
	}

	/**
	 *
	 * @return array 
	 */
	abstract protected function getRoute();

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
	public static function process(Output $Output, $params = array()) {
		$Block = new static();

		$Block->init($params);

		$result = $Block->invoke();

		$Output->bind(get_called_class(), $result);
	}

}