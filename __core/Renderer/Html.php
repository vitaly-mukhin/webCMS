<?php

/**
 * Description of Html
 *
 * @author Vitaliy_Mukhin
 */
class Renderer_Html extends Renderer {
    
    public function render(Output $Output) {
        if($Output->headers()) {
            foreach($Output->headers() as $header => $v) {
                header($header);
            }
        }
        foreach ($Output->export() as $k => $v) {
            echo '<pre>' . $k . ': '. print_r($v, true), '</pre>';
        }
    }
    
}