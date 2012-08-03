<?php

/**
 * Description of Renderer_Http_Json
 *
 * @author Vitaliy_Mukhin
 */
class Renderer_Http_Json extends Renderer_Http {

    /**
     *
     * @param Output_Http $Output
     */
    public function render(Output_Http $Output) {
        return json_encode(array(
                    'result' => $Output->result(),
                    'content' => htmlspecialchars(parent::render($Output))
                ));
    }
}