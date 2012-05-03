<?php

/**
 * Description of Www
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Www extends Flow {
    
    public function process() {
        $next = $this->Input->get(Dispatcher::INPUT_ROUTE)->get('page', 'index');
        $next = ($next) ? $next : 'index';
        return $this->redirect($next);
    }
    
}