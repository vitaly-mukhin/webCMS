<?php

/**
 * Description of Flow_Block_Nav
 *
 * @author Vitaliy_Mukhin
 */
class Flow_Block_Nav extends Flow_AbstractBlock {

	/**
	 *
	 * @return boolean 
	 */
	public function action_default() {
		$this->Output->bind('navArray', array(
			'Головна' => '/',
			'Блоги' => '/blog',
			'Альбоми' => '/album',
			'Цитатник' => '/quote'
		));
	}

}