<?php

/**
 * Description of Abstract
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\Block;
use Core\Block;
use Core\Dispatcher;
use Core\Input;
use Core\Output;
use Core\Renderer;

abstract class Flow extends Block {

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

	const INITIAL_FLOW_STRING = 'block';

	/**
	 *
	 * @param array $params
	 *
	 * @return Block|Block\Flow
	 */
	protected function init($params) {
		parent::init($params);

		$this->initInputs();

		$this->Dispatcher = Dispatcher::di(array(Dispatcher::PARAM_INITIAL_FLOW => self::INITIAL_FLOW_STRING));

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

	/**
	 * @param Input $InputRoute
	 *
	 * @return array
	 */
	abstract protected function getRoute(Input $InputRoute);

	/**
	 *
	 * @return string
	 */
	final protected function invoke() {
		$Output = new Output\Http;

		$this->Dispatcher->flow($this->InputHttp, $Output);

		return $Output;
	}

	/**
	 *
	 * @param Output $Output
	 * @param array  $params
	 */
	public static function process($params = array(), Output $Output = null) {
		$Block = new static();

		$Block->init($params);

		$OutputResult = $Block->invoke();

		if ($Output) {
			$Output->bind(static::getBindName(), Renderer\Http::di()
					->render($OutputResult, $OutputResult->getTemplatePath()));
		}
	}

	/**
	 * Returns the name of variable, where processed result is binded
	 *
	 * @return string
	 */
	public static function getBindName() {
		return get_called_class();
	}

}