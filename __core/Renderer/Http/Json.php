<?php

/**
 * Description of Renderer_Http_Json
 *
 * @author Vitaliy_Mukhin
 */
class Renderer_Http_Json extends Renderer_Http {

    const TPL = '__json.twig';

    /**
     *
     * @param Output_Http $Output
     */
    public function render(Output $Output, $templatePath) {
        /* @var $Output Output_Http */
        $Output->header('Content-Type: text/json');
        $content = parent::render($Output, $templatePath);
        $titles = Block_Head::getPageTitle();

        $result = $this->renderInner(new Output(array(
                    'head' => json_encode(array(
                        'script' => array_values(Block_Head::getJsLinks()),
                        'css' => array_values(Block_Head::getCssLinks())
                    )),
                    'title' => json_encode(htmlspecialchars(reset($titles), ENT_NOQUOTES)),
                    'content' => json_encode(htmlspecialchars($content, ENT_NOQUOTES))
                )), static::TPL);

        return $result;
    }

}