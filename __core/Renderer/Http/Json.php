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

class Json extends Http {

	const TPL = '__json.twig';

	/**
	 * @param Output $Output
	 * @param        $templatePath
	 *
	 * @return string
	 */
	public function render(Output $Output, $templatePath) {
		/* @var $Output Output\Http */
		$Output->header('Content-Type: text/json');
		$content = parent::render($Output, $templatePath);
		$titles  = Block\Head::getPageTitle();

		$result = $this->renderInner(new Output(array('head'    => json_encode(array('script' => array_values(Block\Head::getJsLinks()),
		                                                                             'css'    => array_values(Block\Head::getCssLinks()))),
		                                              'title'   => json_encode(reset($titles), ENT_NOQUOTES),
		                                              'content' => json_encode($content, ENT_NOQUOTES))), static::TPL);

		return $result;
	}

}