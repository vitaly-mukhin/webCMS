<?php

/**
 * Description of Renderer\Http\Json
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\Renderer\Http;
use Core\Renderer\Http;
use Core\Output;
use Core\Block;

class Html extends Http {

	const TPL = '__html.twig';

	/**
	 * @param Output $Output
	 * @param        $templatePath
	 *
	 * @return string
	 */
	public function render(Output $Output, $templatePath) {

		$head = $this->renderInner(new Output(array('jsLinks'   => Block\Head::getJsLinks(),
		                                            'cssLinks'  => Block\Head::getCssLinks(),
		                                            'pageTitle' => Block\Head::getPageTitle(),)), Head::TPL);

		$content = parent::render($Output, $templatePath);

		return $this->renderInner(new Output(array('head' => $head, 'body' => $content)), static::TPL);
	}

}