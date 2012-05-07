<?php

/**
 * Description of Renderer
 *
 * @author Vitaliy_Mukhin
 */
abstract class Renderer {

	/**
	 *
	 * @var Renderer_Engine
	 */
	protected $Engine;

	/**
	 *
	 * @param Renderer_Engine $Engine
	 * @return \Renderer 
	 */
	public function engine(Renderer_Engine $Engine) {
		$this->Engine = $Engine;

		return $this;
	}

	/**
	 *
	 * @param Output $Output
	 * @param string $templatePath
	 * @return string
	 */
	public function render(Output $Output, $templatePath) {
		$content = $this->Engine->render($templatePath, $Output->export());

		return $content;
	}

}