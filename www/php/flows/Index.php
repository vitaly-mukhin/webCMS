<?php

/**
 * Description of Index
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Flow;
use App\Flow;
use App\Block\Head;

class Index extends Flow {

    public function action_default() {
        Head::addPageTitle('index');

        $this->Output->bind('result', 'OK');
    }

}