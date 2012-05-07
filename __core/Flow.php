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
	 * @return boolean|string 
	 */
	protected function getAction($action) {
		if (method_exists($this, static::ACTION_PREFIX . $action)) {
			return static::ACTION_PREFIX . $action;
		}

		return false;
	}

	/**
	 *
	 * @param string $step
	 * @return boolean 
	 */
	protected function redirect($step) {
		$action = $this->getAction($step);

		if ($action) {
			return $this->$action();
		}

		return $step;
	}

	/**
	 * @return boolean|null|string 
	 */
	abstract public function process();

}