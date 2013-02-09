<?php

/**
 * Description of Output
 *
 * @author Mukhenok
 */
namespace Core;

class Output {

	use Output\Template;

	/**
	 *
	 * @var array
	 */
	protected $data = array();

	public function __construct($data = array()) {
		$this->data = $data;
	}

	/**
	 *
	 * @param string     $name
	 * @param mixed|null $value
	 *
	 * @return Output
	 */
	public function bind($name, $value = null) {
		if (is_array($name)) {
			$this->data = array_merge($this->data, $name);
		} else {
			$this->data[$name] = $value;
		}

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
