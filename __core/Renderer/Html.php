<?php

/**
 * Description of Html
 *
 * @author Vitaliy_Mukhin
 */
class Renderer_Html extends Renderer {
    
    public function render(Output $Output, $templatePath) {
        if($Output->headers()) {
            foreach($Output->headers() as $header => $v) {
                header($header);
            }
        }
		
		$Template = $this->Engine->loadTemplate($templatePath);
		
		$content = $Template->render($Output->export());
		
		echo $content;
    }
    
}