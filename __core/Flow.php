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
	const NO_FLOW_FOUND_CLASS = 'NoFlowFound';
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
		$action = is_null($action) ? static::DEFAULT_ACTION : $action;

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
	 * @param string $flowString
	 * @return Flow
	 * @throws ErrorException 
	 */
	final protected function getFlow($flowString) {
		if ($this->existsAction($flowString)) {
			return $this;
		}

		$class = get_called_class();

		if (static::IS_ROOT) {
			$class = substr($class, 0, strrpos($class, self::CLASS_DELIMITER));
		}

		$flowClass = $class . self::CLASS_DELIMITER . ucfirst($flowString);

		if (!class_exists($flowClass)) {
			$flowClass = $this->getNoFlowFound($class);
		}
		/* @var $Flow Flow */
		$Flow = new $flowClass();

		$this->init($this->Input, $this->Output);

		return $Flow;
	}

	/**
	 *
	 * @param string $flowClass
	 * @return string
	 * @throws ErrorException 
	 */
	final protected function getNoFlowFound($flowClass) {
		$flowClass .= self::CLASS_DELIMITER . self::NO_FLOW_FOUND_CLASS;
		while (true) {
			if (class_exists($flowClass)) {
				break;
			}

			$flowArray = explode(self::CLASS_DELIMITER, $flowClass);
			// remove prelast part of class name
			unset($flowArray[count($flowArray) - 2]);

			if (count($flowArray) < 2) {
				throw new ErrorException(sprintf('Flow not found %s', $flowClass));
			}

			$flowClass = implode(self::CLASS_DELIMITER, $flowArray);
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