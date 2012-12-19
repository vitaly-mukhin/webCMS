<?php

/**
 * Description of Renderer
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;
abstract class Renderer {

	/**
	 *
	 * @var Renderer\Engine
	 */
	protected $Engine;

	/**
	 *
	 * @var array
	 */
	protected static $instances;

	protected function __construct() {

	}

	/**
	 *
	 * @return Renderer
	 */
	public static function di() {
		$calledClass = get_called_class();
		if (!empty(static::$instances[$calledClass])) {
			return static::$instances[$calledClass];
		}

		$instance = new static();

		$instance->Engine = $instance->getRendererEngine();

		return static::$instances[$calledClass] = $instance;
	}

	/**
	 *
	 * @return Renderer\Engine
	 */
	private function getRendererEngine() {

		$Engine = new Renderer\Engine();
		$Engine->init();

		return $Engine;
	}

	/**
	 *
	 * @param Output $Output
	 *
	 * @return string
	 */
	public function render(Output $Output, $templatePath) {
		return $this->renderInner($Output, $templatePath);
	}

	/**
	 *
	 * @param Output $Output
	 * @param string $templatePath
	 *
	 * @return string
	 */
	protected function renderInner(Output $Output, $templatePath) {
		$content = $this->Engine->render($Output->export(), $templatePath);

		return $content;
	}

}