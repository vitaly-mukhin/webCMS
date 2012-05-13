<?php

/**
 * Description of Flow
 *
 * @author Mukhenok
 */
abstract class Flow {

	const ACTION_PREFIX = 'action_';

	/**
	 *
	 * @var Input_Http
	 */
	protected $Input;

	/**
	 *
	 * @var Output_Http 
	 */
	protected $Output;

	/**
	 *
	 * @var string
	 */
	protected $runnedAction;

	/**
	 * Set input/output objects
	 *
	 * @param Input $Input
	 * @param Output $Output 
	 */
	public function init(Input_Http $Input, Output_Http $Output) {
		$this->Input = $Input;

		$this->Output = $Output;
	}

	/**
	 *
	 * @param string $action
	 * @return string|boolean
	 */
	public function action($action) {

		$this->callPre($action);

		$result = $this->call($action);

		$this->callPost($result);

		return $result;
	}

	/**
	 *
	 * @param string $action 
	 */
	protected function callPre($action) {
		$this->runnedAction = $action;
	}

	/**
	 *
	 * @param string|boolean $result 
	 */
	protected function callPost($result) {
		if ($result !== true) {
			$this->runnedAction = false;
		}
	}

	/**
	 *
	 * @param string $action
	 * @return string|boolean 
	 */
	protected function call($action) {
		return $this->{$this->getAction($action)}();
	}

	/**
	 *
	 * @param string $action
	 * @return boolean
	 */
	protected function existsAction($action) {
		return method_exists($this, $this->getAction($action));
	}

	/**
	 *
	 * @param string $action
	 * @return string
	 */
	protected function getAction($action) {
		return static::ACTION_PREFIX . $action;
	}

	/**
	 *
	 * @param string $step
	 * @return boolean 
	 */
	protected function redirect($step) {
		if ($this->existsAction($step)) {
			return $this->action($step);
		}

		return $step;
	}

	/**
	 * @return boolean|string 
	 */
	abstract public function process();

}