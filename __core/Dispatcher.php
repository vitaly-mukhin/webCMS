<?php

/**
 * Description of Dispatcher
 *
 * @author Vitaliy_Mukhin
 */
class Dispatcher {

	/**
	 *
	 * @var Flow
	 */
	private $initialFlow;

	const ROUTE_IN_GET = 'route';
	const NO_FLOW_FOUND = 'noFlowFound';
	const MODE_FLOW = 'flow';
	const MODE_FOLDER = 'mode';
	const MODE_ROUTER = 'router';
	const FLOW_FINISHED = true;

	public function __construct() {
		
	}

	/**
	 *
	 * @param Input_Config $Config
	 * @return \Dispatcher 
	 */
	public function init(Input_Config $Config) {
		$this->initModeEnv();

		$this->setInitialFlow($Config->get(Dispatcher::MODE_FLOW));

		return $this;
	}

	/**
	 *
	 * @return Dispatcher
	 */
	private function initModeEnv() {

		$this->addModeAutoloaders(PATH_MODE);

		return $this;
	}

	/**
	 *
	 * @param string $flowString
	 * @return \Dispatcher 
	 */
	public function setInitialFlow($flowString) {
		$flowObject = $this->getFlow($flowString);
		$this->initialFlow = $flowObject;

		return $this;
	}

	/**
	 *
	 * @param string $modeFolder 
	 */
	private function addModeAutoloaders($modeFolder) {
		$baseFolder = $modeFolder . DIRECTORY_SEPARATOR . 'php';

		// autoloader for Flow_*
		$FlowLoader = new Loader();
		$FlowLoader
				->setBaseFolder($baseFolder . DIRECTORY_SEPARATOR . 'flows')
				->setIgnoreFirstPart(true)
				->useFilePrefix(false);
		Autoloader::add($FlowLoader);

		// autoloader for Block_*
		$BlockLoader = new Loader();
		$BlockLoader
				->setBaseFolder($baseFolder . DIRECTORY_SEPARATOR . 'blocks')
				->setIgnoreFirstPart(true)
				->useFilePrefix(false);
		Autoloader::add($BlockLoader);
	}

	/**
	 *
	 * @param string $flowString
	 * @return Flow
	 * @throws ErrorException 
	 */
	private function getFlow($flowString, $BaseFlow = null) {
		$class = 'Flow';
		if (
				!is_null($BaseFlow) &&
				get_class($BaseFlow) !== 'Flow' &&
				($BaseFlow != $this->initialFlow || get_class($BaseFlow) == 'Flow_Block')
		) {
			$class = get_class($BaseFlow);
		}

		$flowClass = $class . '_' . ucfirst($flowString);

		if (!class_exists($flowClass)) {
			$flowClass = $this->getNoFlowFound($flowClass);
		}
		/* @var $Flow Flow */
		$Flow = new $flowClass();

		return $Flow;
	}

	/**
	 *
	 * @param string $flowClass
	 * @return string
	 * @throws ErrorException 
	 */
	private function getNoFlowFound($flowClass) {
		$flowClass .= '_NoFlowFound';
		while (true) {
			if (class_exists($flowClass)) {
				break;
			}

			$flowArray = explode('_', $flowClass);
			unset($flowArray[count($flowArray) - 2]);

			if (count($flowArray) < 2) {
				throw new ErrorException(sprintf('Flow not found %s', $flowClass));
			}

			$flowClass = implode('_', $flowArray);
		}

		return $flowClass;
	}

	/**
	 *
	 * @return string
	 * @throws ErrorException 
	 */
	public function flow(Input_Http $Input = null) {
		// initiating a Output_Http object, and setting its default params
		$Output = new Output_Http();
		$Output->renderer(new Renderer_Http);


		$result = null;
		$i = 0;

		$Flow = $this->initialFlow;
		// TODO 2012-05-15: move Flow processing inside the Flow class, for next time diagramm:
		//	FlowRoot::pre()
		//	FlowRoot::call() {
		//		FlowInner::pre()
		//		FlowInner::call() {
		//			FlowSubInner::pre()
		//			FlowSubInner::call()
		//			FlowSubInner::past()
		//		}
		//		FlowInner::past()
		//	}
		//	FlowRoot::past()
		while ((is_null($result) || is_string($result))) {
			/* @var $Flow Flow */
			$Flow->init($Input, $Output);

			$result = $Flow->action();

			if ($result === self::FLOW_FINISHED) {
				break;
			}

			$result = $result ? : self::NO_FLOW_FOUND;

			if (!empty($result) && is_string($result)) {
				try {
					$Flow = $this->getFlow($result, $Flow);
				} catch (ErrorException $E) {
					$Flow = false;
				}
				if (!$Flow) {
					$result = false;
				}
			}

			if (++$i > 100) {
				throw new ErrorException('Too much iterations - proper flow not found');
			}
		}

		return $this->render($Output, $this->getTemplatePath($Flow));
	}

	/**
	 *
	 * @param Flow $Flow
	 * @return string 
	 */
	private function getTemplatePath(Flow $Flow) {
//		$Flow->getTemplatePath();
		$array = explode('_', strtolower(str_replace('Flow_', '', get_class($Flow))));

		return implode(DIRECTORY_SEPARATOR, $array) . '.twig';
	}

	/**
	 *
	 * @param Output $Output 
	 */
	public function render(Output $Output, $templatePath) {
		$Renderer = $Output->renderer();
		$Renderer->engine($this->getRendererEngine());
		return $Renderer->render($Output, $templatePath);
	}

	/**
	 *
	 * @return Renderer_Engine 
	 */
	private function getRendererEngine() {

		$Engine = new Renderer_Engine();
		$Engine->init();

		return $Engine;
	}

}