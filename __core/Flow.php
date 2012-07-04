<?php

/**
 * Description of Flow
 *
 * @author Mukhenok
 */
class Flow {

	const ACTION_PREFIX = 'action_';
	const IS_ROOT = false;

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

	const DEFAULT_ACTION = 'default';
	const CLASS_DELIMITER = '_';
	const FLOW_NOT_FOUND_CLASS = 'NoFlowFound';
	const TEMPLATE_FILE_EXTENSION = '.twig';

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
	public function action($action = null) {
		$action = is_null($action) ? $this->getDefaultAction() : $action;

		$this->Output->setTemplatePath($this->getTemplatePath($action));

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
		if ($this->existsAction($action)) {
			return $this->{$this->getAction($action)}();
		} else {
			return $this->runChildFlow($action);
		}
	}

	/**
	 *
	 * @param string $Flow
	 * @return string 
	 */
	protected function getTemplatePath($action) {
		$array = explode(self::CLASS_DELIMITER, strtolower(get_called_class()));
		// unset standard Flow
		unset($array[0]);
		if (static::IS_ROOT) {
			// unset standard Www
			unset($array[1]);
		}
		$array[] = $action;

		return implode(DIRECTORY_SEPARATOR, $array) . self::TEMPLATE_FILE_EXTENSION;
	}

	/**
	 * @param string $action
	 */
	protected function runChildFlow($action) {
		$Flow = $this->getFlow($action);
		if ($Flow != $this) {
			$Flow->init($this->Input, $this->Output);
			$Flow->action();
		} else {
			$this->action($action);
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getDefaultAction() {
		return static::DEFAULT_ACTION;
	}

	/**
	 * Get the current or child Flow, that might proceed with $childFlowSuffix
	 *
	 * @param string $childFlowSuffix
	 * @return Flow
	 * @throws ErrorException 
	 */
	final protected function getFlow($childFlowSuffix) {
		// for being able to redirect inside current Flow
		if ($this->existsAction($childFlowSuffix)) {
			return $this;
		}

		$currentClass = get_called_class();

		if (static::IS_ROOT) {
			$currentClass = substr($currentClass, 0, strrpos($currentClass, self::CLASS_DELIMITER));
		}

		$flowClass = $this->getChildFlowName($currentClass, $childFlowSuffix);

		if (!class_exists($flowClass)) {
			$flowClass = $this->getNoFlowFoundClass($flowClass);
		}
		/* @var $Flow Flow */
		$Flow = new $flowClass();

		return $Flow;
	}

	/**
	 * Build class name for child flow (by using $childFlowSuffix)
	 * 
	 * @param string $flowClass
	 * @param string $childFlowSuffix
	 * @return string
	 */
	protected function getChildFlowName($flowClass, $childFlowSuffix) {
		return $flowClass . self::CLASS_DELIMITER . ucfirst($childFlowSuffix);
	}

	/**
	 * Transform $flowClass into the parent NoFlowFound class
	 * 
	 * Example 1:
	 *     $flowClass = Flow_Block_Auth_Reset
	 * Return:
	 *     Flow_Block_Auth_NoFlowFound
	 * 
	 * Example 2:
	 *     $flowClass = Flow_Block_Auth_NoFlowFound
	 * Return:
	 *     Flow_Block_NoFlowFound
	 * 
	 * @param string $flowClass
	 * @return string
	 */
	protected function buildNoFloFoundClass($flowClass) {
		// remove _NoFlowFound from the flow class name
		$flowClassShort = str_replace(self::FLOW_NOT_FOUND_CLASS, '', $flowClass);
		$flowClassShort = trim($flowClassShort, self::CLASS_DELIMITER);

		// remove prelast part of class name
		$flowClassShort = substr($flowClassShort, 0, strrpos($flowClassShort, self::CLASS_DELIMITER));
		$flowClassShort = trim($flowClassShort, self::CLASS_DELIMITER);

		return $this->getChildFlowName($flowClassShort, self::FLOW_NOT_FOUND_CLASS);
	}

	/**
	 * Get the nearest relative NoFlowFound class
	 * 
	 * Example:
	 *     $flowClass = Flow_Block_Auth_Reset
	 * 
	 * Return:
	 *     Flow_Block_Auth_NoFlowFound
	 *     Flow_Block_NoFlowFound
	 *     Flow_NoFlowFound
	 *
	 * @param string $flowClass
	 * @return string
	 * @throws ErrorException 
	 */
	final protected function getNoFlowFoundClass($flowClass) {
		while (true) {
			$flowClass = $this->buildNoFloFoundClass($flowClass);

			if (class_exists($flowClass)) {
				break;
			}
		}

		return $flowClass;
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

}