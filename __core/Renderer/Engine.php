<?php

/**
 * A wrapper for a Twig/Smarty/etc template engine.
 *
 * @author Mukhenok
 */
class Renderer_Engine {

	/**
	 *
	 * @var Twig_Environment
	 */
	private $core;

	/**
	 *
	 * @return \Renderer_Engine 
	 */
	public function init() {
		require_once PATH_LIBS . DIRECTORY_SEPARATOR . 'Twig' . DIRECTORY_SEPARATOR . 'Autoloader.php';

		Twig_Autoloader::register();

		$Twig_Loader = new Twig_Loader_Filesystem(PATH_MODE_TEMPLATES);

		$this->core = new Twig_Environment($Twig_Loader, array(
					'cache'=>PATH_MODE_TEMPLATES_C,
					'debug'=>($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
				));

		return $this;
	}

	/**
	 *
	 * @param string $templatePath
	 * @param array $data 
	 * @return string
	 */
	public function render($templatePath, $data) {

		$Template = $this->core->loadTemplate($templatePath);

		$content = $Template->render($data);

		return $content;
	}

}