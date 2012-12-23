<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block\Flow;
class Reg extends \App\Block\Flow {

    /**
     * @param Input $InputRoute
     *
     * @return type
     */
    protected function getRoute(Input $InputRoute) {
        return array('action' => 'auth', 'step' => 'reg');
    }

}