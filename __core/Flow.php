<?php

/**
 * Description of Flow
 *
 * @author Mukhenok
 */

namespace Core;
use Core;

class Flow {

	use Tpl;

	const ACTION_PREFIX = 'action_';

	const IS_ROOT = false;

	const DEFAULT_ACTION = 'default';

	const CLASS_DELIMITER = '\\';

	const FLOW_NOT_FOUND_CLASS = 'NoFlowFound';

	const TEMPLATE_FILE_EXTENSION = '.twig';

	//	const CLASS_DELIMITER         = '_';

	/**
	 *
	 * @var Input\Http
	 */
	protected $Input;

	/**
	 *
	 * @var Output\Http
	 */
	protected $Output;

	/**
	 *
	 * @var string
	 */
	protected $runnedAction;

	/**
	 * @param Input\Http  $Input
	 * @param Output\Http $Output
	 */
	public function init(Input\Http $Input, Output\Http $Output) {
		$this->Input = $Input;

		$this->Output = $Output;
	}

	/**
	 *
	 * @param string $action
	 *
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
	 * @return string
	 */
	public function getDefaultAction() {
		return static::DEFAULT_ACTION;
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
	 *
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
	 * @param string $action
	 *
	 * @return bool|string
	 */
	protected function runChildFlow($action) {
		$Flow = $this->getFlow($action);
		if ($Flow != $this) {
			$Flow->init($this->Input, $this->Output);

			return $Flow->action();
		} else {
			return $this->action($action);
		}
	}

	/**
	 * Get the current or child Flow, that might proceed with $childFlowSuffix
	 *
	 * @param string $childFlowSuffix
	 *
	 * @throws \Exception
	 * @return Flow
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
			throw new \Exception('Flow class <b>' . $flowClass . ' not found</b>');
			//			$flowClass = $this->getNoFlowFoundClass($flowClass);
		}
		/* @var $Flow Flow */
		$Flow = new $flowClass;

		return $Flow;
	}

	/**
	 * Get the nearest relative NoFlowFound class
	 *
	 * Example:
	 *     $flowClass = Flow_Block\Auth_Reset
	 *
	 * Return:
	 *     Flow_Block\Auth_NoFlowFound
	 *     Flow_Block_NoFlowFound
	 *     Flow_NoFlowFound
	 *
	 * @param string $flowClass
	 *
	 * @return string
	 * @throws \ErrorException
	 */
	final protected function getNoFlowFoundClass($flowClass) {
		while (true) {
			$flowClass = $this->buildNoFloFoundClass($flowClass);

			if (class_exists($flowClass)) {
				break;
			} else {
				$flowClass = $this->buildNoFloFoundClass(__NAMESPACE__ . $flowClass);
			}
		}

		return $flowClass;
	}

	/**
	 * Transform $flowClass into the parent NoFlowFound class
	 *
	 * Example 1:
	 *     $flowClass = Flow\Block\Auth\Reset
	 * Return:
	 *     Flow\Block\Auth\NoFlowFound
	 *
	 * Example 2:
	 *     $flowClass = Flow\Block\Auth\NoFlowFound
	 * Return:
	 *     Flow\Block\NoFlowFound
	 *
	 * @param string $flowClass
	 *
	 * @return string
	 */
	protected function buildNoFloFoundClass($flowClass) {
		// remove _NoFlowFound from the flow class name
		$flowClassShort = str_replace(static::FLOW_NOT_FOUND_CLASS, '', $flowClass);
		$flowClassShort = trim($flowClassShort, self::CLASS_DELIMITER);

		// remove prelast part of class name
		$flowClassShort = substr($flowClassShort, 0, strrpos($flowClassShort, self::CLASS_DELIMITER));
		$flowClassShort = trim($flowClassShort, self::CLASS_DELIMITER);

		return $this->getChildFlowName($flowClassShort, self::FLOW_NOT_FOUND_CLASS);
	}

	/**
	 * Build class name for child flow (by using $childFlowSuffix)
	 *
	 * @param string $flowClass
	 * @param string $childFlowSuffix
	 *
	 * @return string
	 */
	protected function getChildFlowName($flowClass, $childFlowSuffix) {
		return $flowClass . self::CLASS_DELIMITER . ucfirst($childFlowSuffix);
	}

	/**
	 *
	 * @param string $action
	 *
	 * @return boolean
	 */
	protected function existsAction($action) {
		return method_exists($this, $this->getAction($action));
	}

	/**
	 *
	 * @param string $action
	 *
	 * @return string
	 */
	protected function getAction($action) {
		return static::ACTION_PREFIX . $action;
	}

}
