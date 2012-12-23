<?php

/**
 * Description of Flow_Block
 *
 * @author Vitaliy_Mukhin
 */
namespace App;
class Block extends Flow {

    public function callPre($action) {
        Block\Auth::process();

        parent::callPre($action);
    }

    public function action_default() {
        $this->runChildFlow($this->Input->get(\Core\Input\Http::INPUT_ROUTE)->get('action'));
    }

}