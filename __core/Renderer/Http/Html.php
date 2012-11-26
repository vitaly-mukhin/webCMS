<?php

/**
 * Description of Renderer_Http_Json
 *
 * @author Vitaliy_Mukhin
 */
class Renderer_Http_Html extends Renderer_Http {

	const TPL = '__html.twig';

	/**
	 *
	 * @param \Output|\Output_Http $Output
	 * @param string               $templatePath
	 *
	 * @return string
	 */
	public function render(Output $Output, $templatePath) {

		$head = $this->renderInner(new Output(array('jsLinks'   => Block_Head::getJsLinks(),
		                                            'cssLinks'  => Block_Head::getCssLinks(),
		                                            'pageTitle' => Block_Head::getPageTitle(),)),
		                           Block_Head::TPL);

		$content = parent::render($Output, $templatePath);

		return $this->renderInner(new Output(array('head' => $head, 'body' => $content)), static::TPL);
	}

}