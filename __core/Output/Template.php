<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mukhenok
 * Date: 09.02.13
 * Time: 16:14
 */

namespace Core\Output;

trait Template {

	/**
	 *
	 * @var \Core\Renderer
	 */
	protected $Renderer;

	/**
	 *
	 * @var string
	 */
	protected $templatePath;

	/**
	 *
	 * @param string $templatePath
	 *
	 * @return self
	 */
	public function setTemplatePath($templatePath) {
		$this->templatePath = $templatePath;

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function getTemplatePath() {
		return $this->templatePath;
	}

}
