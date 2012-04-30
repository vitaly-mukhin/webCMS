<?php

/**
 * Description of Www
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Www extends Flow {
    
    public function process() {
        return $this->redirect($this->Input->get(Dispatcher::ROUTE, 'index'));
    }
    
    public function action_aindex() {
        var_dump('aaaaaaa');
        
        return true;
    }
    
}