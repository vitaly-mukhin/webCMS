<?php

/**
 * Description of Output
 *
 * @author Mukhenok
 */
class Output {

	/**
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 *
	 * @var Renderer
	 */
	protected $Renderer;

	/**
	 *
	 * @param string $name
	 * @param mixed|null $value
	 * @return Output 
	 */
	public function bind($name, $value = null) {
		$this->data[$name] = $value;

		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function export() {
		return $this->data;
	}

}