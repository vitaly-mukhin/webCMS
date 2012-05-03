<?php

/**
 * Description of Renderer
 *
 * @author Vitaliy_Mukhin
 */
abstract class Renderer {
	
	/**
	 *
	 * @var Twig_Environment
	 */
	protected $Engine;
	
	public function __construct($Engine) {
		$this->Engine = $Engine;
	}
    
    abstract public function render(Output $Output, $templatePath);
    
}