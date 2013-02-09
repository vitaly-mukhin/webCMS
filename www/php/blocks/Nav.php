<?php

/**
 * Description of Nav block
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Block;

use Core\Input;

class Nav extends \Core\Block {

	protected function invoke() {
		$Output = parent::invoke();

		$Output->bind('navArray', array('Головна' => '/',
		                                'Блоги'   => '/blog',
		                                'Альбоми' => '/gallery' //			'Цитатник' => '/quote'
		                          ));
		return $Output;
	}

}
