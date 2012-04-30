<?php

/**
 * Description of NoFlowFound
 *
 * @author Vitaliy_Mukhin
 */
class Flow_NoFlowFound extends Flow {
    
    public function process() {
        return $this->redirect('default');
    }
    
    public function action_default() {
        $this->Output->bind('result', 'no flow found');
        
        return true;
    }
    
}