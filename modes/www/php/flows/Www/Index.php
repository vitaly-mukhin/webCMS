<?php

/**
 * Description of Index
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Index extends Flow {
    
    public function process() {
        return $this->redirect('aaa');
    }
    
    public function action_aaa() {
        var_dump('aaa');
    }
    
}