<?php

/**
 * Description of Flow_Block_Nav
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block;

use Core\Block;

class Nav1 extends Block {

	/**
	 *
	 * @param array        $params
	 * @param \Core\Output $Output
	 *
	 * @return boolean
	 */
	public static function process($params = array(), \Core\Output $Output = null) {
		$Output->bind('navArray', array('Головна' => '/',
		                                'Блоги'   => '/blog',
		                                'Альбоми' => '/gallery' //			'Цитатник' => '/quote'
		                          ));
	}

}
