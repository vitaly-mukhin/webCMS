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

	const ROUTE_IN_GET = 'route';

	const MODE_ROUTER = 'router';

	const PARAM_MODE_CONFIG = 'modeConfig';

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
	 * @return static
	 */
	protected function init() {
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
		$base_folder = $modeFolder . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR;

		// autoloader for Flow_*
		Autoloader::add(\App\LoaderNames::i($base_folder . 'flows', true, false));

		// autoloader for Block_*
		Autoloader::add(\App\LoaderNames::i($base_folder . 'blocks', true, false));

		// autoloader for models
		Autoloader::add(\App\LoaderNames::i($base_folder . 'model', null, false));
	}

	/**
	 * @param Input\Http  $Input
	 * @param Output\Http $Output
	 */
	public function flow(Input\Http $Input = null, Output\Http $Output) {
		$Flow = \App\Flow::i($Input, $Output);

		$Flow->action($this->initialFlow);
	}

}
