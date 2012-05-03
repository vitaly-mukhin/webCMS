<?php

/**
 * Description of Index
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Index extends Flow {
    
    public function process() {
        return $this->redirect($this->Input->get(Dispatcher::INPUT_ROUTE)->get('action', 'index'));
    }
    
    public function action_index() {
        $this->Output->bind('result', 'OK');
        
        return true;
    }
    
    public function action_root() {
        $this->Output->bind('result', 'ROOT');
        
        return true;
    }
    
}