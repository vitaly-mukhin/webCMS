<?php

/**
 * Description of Login
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block\Flow;

class Nav extends \App\Block\Flow {

    /**
     * @param \Core\Input $InputRoute
     *
     * @return array
     */
    protected function getRoute(\Core\Input $InputRoute) {
        return array('action' => 'nav');
    }

}