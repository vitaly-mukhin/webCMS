<?php

/**
 * Description of Block
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;
class Block {

	/**
	 *
	 * @var array
	 */
	protected $params = array();

	/**
	 *
	 * @param array $params
	 *
	 * @return Block
	 */
	protected function init($params) {
		$this->params = (array) $params;

		return $this;
	}

	/**
	 *
	 * @param array $params
	 */
	public static function process($params = array()) {

	}

}