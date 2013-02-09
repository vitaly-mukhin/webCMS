<?php

/**
 * Description of Dispatcher
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;
use VM\Autoloader;

class Dispatcher {

	/**
	 * @var Flow
	 */
	private $initialFlow;

	const ROUTE_IN_GET       = 'route';
	const MODE_ROUTER        = 'router';
	const PARAM_MODE_CONFIG  = 'modeConfig';
	const PARAM_INITIAL_FLOW = 'initialFlow';

	/**
	 * @var Input\Config
	 */
	private $modeConfig;

	private function __construct() {

	}

	private function __clone() {

	}

	/**
	 * @param array $params
	 *
	 * @return self
	 */
	public static function di($params = array()) {
		$Dispatcher = new self();
		$Dispatcher->init();
		$Dispatcher->setOptions($params);

		return $Dispatcher;
	}

	protected function setOptions($params) {
		foreach ($params as $k => $v) {
			$method = 'set' . ucfirst($k);
			if (method_exists($this, $method) && ($method != __FUNCTION__)) {
				$this->$method($v);
			}
		}
	}

	/**
	 * @return self
	 */
	public function init() {
		$this->initModeEnv();

		return $this;
	}

	/**
	 * @return self
	 */
	private function initModeEnv() {

		$this->addModeAutoloaders(PATH_MODE);

		return $this;
	}

	/**
	 * @param string $flowString
	 *
	 * @return self
	 */
	public function setInitialFlow($flowString) {
		$this->initialFlow = $flowString;

		return $this;
	}

	/**
	 * @param Input\Config $ModeConfig
	 *
	 * @return self
	 */
	public function setModeConfig(Input\Config $ModeConfig) {
		$this->modeConfig = $ModeConfig;

		return $this;
	}

	/**
	 * @param string $modeFolder
	 */
	private function addModeAutoloaders($modeFolder) {
		$baseFolder = $modeFolder . DIRECTORY_SEPARATOR . 'php';

		// autoloader for Flow_*
		$FlowLoader = new \App\LoaderNames();
		$FlowLoader->setBaseFolder($baseFolder . DIRECTORY_SEPARATOR . 'flows')->setIgnoreFirstPart(true)->useFilePrefix(false);
		Autoloader::add($FlowLoader);

		// autoloader for Block_*
		$BlockLoader = new \App\LoaderNames();
		$BlockLoader->setBaseFolder($baseFolder . DIRECTORY_SEPARATOR . 'blocks')->setIgnoreFirstPart(true)->useFilePrefix(false);
		Autoloader::add($BlockLoader);

		// autoloader for models
		$BlockLoader = new \App\LoaderNames();
		$BlockLoader->setBaseFolder($baseFolder . DIRECTORY_SEPARATOR . 'model')->useFilePrefix(false);

		Autoloader::add($BlockLoader);
	}

	/**
	 * @param Input\Http  $Input
	 * @param Output\Http $Output
	 */
	public function flow(Input\Http $Input = null, Output\Http $Output) {
		$Flow = new \App\Flow();
		$Flow->init($Input, $Output);

		$Flow->action($this->initialFlow);
	}

}
