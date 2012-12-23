<?php

/**
 * This is a basic class, which will provide a basic features for providing
 * a convenient read access to array of data
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;
class Input {

	/**
	 *
	 * @var array
	 */
	protected $data;

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

		$this->data = $data;
	}

	/**
	 *
	 * @return array
	 */
	public function export() {
		return $this->data;
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
		return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
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