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
	 * @return string
	 */
	public function render(Output_Http $Output) {
		$templatePath = $Output->getTemplatePath();
		$content = $this->Engine->render($templatePath, $Output->export());

		return $content;
	}

}