<?php

/**
 * This is a basic class, which will provide a basic features for providing
 * a convenient read access to array of data
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;

use Core\Model\Get;

class Input {

	use Get;

	/**
	 * Throws Exception if data are incompatible
	 *
	 * @param array $data
	 *
	 * @throws \ErrorException
	 */
	public function __construct(array $data = array()) {
		if (!is_array($data)) {
			throw new \ErrorException('Source data are incompatible');
		}

		$this->traitSetData($data);
	}

	/**
	 *
	 * @return array
	 */
	public function export() {
		return $this->data;
	}

	/**
	 * @return array
	 */
	public function keys() {
		return array_flip(self::$dataKeys);
	}

	/**
	 * Get a value from a holded data array.
	 *
	 * @param mixed $key
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get($key, $default = null) {
		return $this->traitIsset($key) ? $this->__get($key) : $default;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name) {
		return $this->traitGetter($name);
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @throws \ErrorException
	 */
	public function __set($name, $value) {
		throw new \ErrorException('AAAAAAAAAAAAAAAAAAAAA');
	}

	/**
	 * Returns TRUE, if there are no data
	 *
	 * @return boolean
	 */
	public function isEmpty() {
		return empty($this->data);
	}

}
