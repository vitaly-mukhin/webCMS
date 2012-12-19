<?php

/**
 * A wrapper for a Twig/Smarty/etc template engine.
 *
 * @author Mukhenok
 */
namespace Core\Renderer;
class Engine {

	/**
	 *
	 * @var \Twig_Environment
	 */
	private $core;

	/**
	 *
	 * @return self
	 */
	public function init() {
		require_once PATH_LIBS . DIRECTORY_SEPARATOR . 'Twig' . DIRECTORY_SEPARATOR . 'Autoloader.php';

		\Twig_Autoloader::register();

		$Twig_Loader = new \Twig_Loader_Filesystem(PATH_MODE_TEMPLATES);

		$this->core = new \Twig_Environment($Twig_Loader, array('cache'       => PATH_MODE_TEMPLATES_C,
		                                                        'auto_reload' => 1,
		                                                        'debug'       => ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')));

		return $this;
	}

	/**
	 *
	 * @param string $templatePath
	 * @param array  $data
	 *
	 * @return string
	 */
	public function render($data, $templatePath) {

		if (!file_exists(PATH_MODE_TEMPLATES . DIRECTORY_SEPARATOR . $templatePath)) {
			return '<!-- template "' . htmlspecialchars($templatePath, ENT_IGNORE, 'UTF-8') . '" not found -->';
		}

		$Template = $this->core->loadTemplate($templatePath);

		$content = $Template->render($data);

		return $content;
	}

}