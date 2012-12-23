<?php

/**
 * Description of Flow_Block_Nav
 *
 * @author Vitaliy_Mukhin
 */
namespace App\Flow\Block;
use App\Flow\Block;

class Nav extends Block {

	/**
	 *
	 * @return boolean 
	 */
	public function action_default() {
		$this->Output->bind('navArray', array(
			'Головна' => '/',
			'Блоги' => '/blog',
			'Альбоми' => '/gallery'
//			'Цитатник' => '/quote'
		));
	}

}