<?php

/**
 * Description of Dispatcher
 *
 * @author Vitaliy_Mukhin
 */
class Dispatcher {

	/**
	 *
	 * @var Dispatcher
	 */
	private static $instance;

	/**
	 *
	 * @var Router
	 */
	private $Router;

	/**
	 *
	 * @var Input_Config
	 */
	private $Config;

	/**
	 *
	 * @var Flow
	 */
	private $ModeFlow;

	const ROUTE_IN_GET = 'route';
	const NO_FLOW = 'noFlowFound';
	const MODE_FLOW = 'flow';
	const MODE_FOLDER = 'mode';
	const MODE_ROUTER = 'router';

	public function __construct() {
		
	}

	/**
	 *
	 * @param Input_Config $Config
	 * @return \Dispatcher 
	 */
	public function init(Input_Config $Config) {
		$this->Config = $Config;

		$this->initModeEnv($this->Config->get(Dispatcher::MODE_FOLDER));

		$this->initModeRouter($this->Config->get(Dispatcher::MODE_ROUTER));

		$this->initModeFlow($this->Config->get(Dispatcher::MODE_FLOW));

		return $this;
	}

	/**
	 * Setting up the mode Router
	 *
	 * @param Input_Config $routerConfig
	 * @return \Dispatcher 
	 */
	private function initModeRouter(Input_Config $routerConfig) {
		$this->Router = new Router();
		$this->Router->setRouteMask($routerConfig->get('mask'));

		return $this;
	}

	/**
	 *
	 * @param string $modeFolder
	 * @return \Dispatcher
	 * @throws ErrorException 
	 */
	private function initModeEnv($modeFolder) {
		if (!$modeFolder) {
			throw new ErrorException('mode folder must be set!');
		}
        
		define('PATH_MODE_TEMPLATES', PATH_MODE . DIRECTORY_SEPARATOR . 'templates');
		define('PATH_MODE_TEMPLATES_C', PATH_MODE_TEMPLATES . DIRECTORY_SEPARATOR . '__c');

		$this->addModeAutoloaders(PATH_MODE);

		return $this;
	}

	/**
	 *
	 * @param string $flow
	 * @return \Dispatcher 
	 */
	private function initModeFlow($flow) {
		$flowObject = $this->getFlow($flow);
		$this->ModeFlow = $flowObject;

		return $this;
	}

	/**
	 *
	 * @param type $modeFolder 
	 */
	private function addModeAutoloaders($modeFolder) {
		$baseFolder = $modeFolder . DIRECTORY_SEPARATOR . 'php';
		$FlowLoader = new Loader();
		$FlowLoader
				->setBaseFolder($baseFolder . DIRECTORY_SEPARATOR . 'flows')
				->setIgnoreFirstPart(true)
				->useFilePrefix(false);
		Autoloader::add($FlowLoader);
	}

	/**
	 *
	 * @param string $flowString
	 * @return Flow
	 * @throws ErrorException 
	 */
	private function getFlow($flowString, $BaseFlow = null) {
		$class = (!is_null($BaseFlow) && get_class($BaseFlow) !== 'Flow' && $BaseFlow != $this->ModeFlow) ? get_class($BaseFlow) : 'Flow';

		$flowClass = $class . '_' . ucfirst($flowString);

		if (!class_exists($flowClass)) {
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
		}
		/* @var $Flow Flow */
		$Flow = new $flowClass();

		return $Flow;
	}

	/**
	 *
	 * @return string
	 * @throws ErrorException 
	 */
	public function flow() {
		$InputGet = new Input($_GET);
		$uparsedRoute = $InputGet->get(self::ROUTE_IN_GET, '');
		$InputRouter = new Input($this->Router->parse($uparsedRoute));

		$Input = new Input_Http(array(
					Input_Http::INPUT_ROUTE=>$InputRouter,
					Input_Http::INPUT_GET=>$InputGet,
					Input_Http::INPUT_POST=>$_POST,
					Input_Http::INPUT_COOKIE=>$_COOKIE
				));

		// initiating a Output_Http object, and setting its default params
		$Output = new Output_Http();
		$Output->renderer(new Renderer_Http);


		$result = null;
		$i = 0;
		$Flow = $this->ModeFlow;
		while ((is_null($result) || is_string($result)) && ++$i) {
			/* @var $Flow Flow */
			$Flow->init($Input, $Output);

			$result = $Flow->process();

			if ($result === true) {
				break;
			}

			if ($result === false) {
				$result = Dispatcher::NO_FLOW;
			}

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

			if ($i > 100) {
				throw new ErrorException('Too much iterations');
			}
		}

		return $this->render($Output, $this->getTemplate($Flow));
	}

	private function getTemplate(Flow $Flow) {
		$array = explode('_', str_replace('Flow_', '', get_class($Flow)));

		array_walk($array, function(&$v) {
					$v = strtolower($v);
				});

		return implode(DIRECTORY_SEPARATOR, $array) . '.tpl';
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
	 * @return \Twig_Environment 
	 */
	private function getRendererEngine() {

		$Engine = new Renderer_Engine();
		$Engine->init();

		return $Engine;
	}

}