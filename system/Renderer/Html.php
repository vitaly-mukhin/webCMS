<?php

/**
 * Description of Html
 *
 * @author Vitaliy_Mukhin
 */
class Renderer_Html extends Renderer {
    
    public function render(Output $Output) {
        echo '<pre>' . print_r($Output, true), '</pre>';
    }
    
}