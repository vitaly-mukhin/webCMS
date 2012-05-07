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
	 * @param null|Renderer $Renderer
	 * @return Renderer
	 */
	public function renderer(Renderer $Renderer = null) {
		if (!is_null($Renderer)) {
			$this->Renderer = $Renderer;
		}


		return $this->Renderer;
	}

	/**
	 *
	 * @return array
	 */
	public function export() {
		return $this->data;
	}

}