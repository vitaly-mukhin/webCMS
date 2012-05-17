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
		$this->initialFlow = $flowString;

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
	 * @return string
	 * @throws ErrorException 
	 */
	public function flow(Input_Http $Input = null) {
		// initiating a Output_Http object, and setting its default params
		$Output = new Output_Http();
		$Output->renderer(new Renderer_Http);

		$Flow = new Flow();
		$Flow->init($Input, $Output);

		$Flow->action($this->initialFlow);

		return $this->render($Output);
	}

	/**
	 *
	 * @param Output_Http $Output 
	 */
	public function render(Output_Http $Output) {
		// TODO 2012-05-18: move this logic to a Output::render() ?
		$Renderer = $Output->renderer();
		$Renderer->engine($this->getRendererEngine());

		return $Renderer->render($Output);
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