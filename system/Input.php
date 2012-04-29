<?php

/**
 * This is a basic class, which will provide a basic features of accepting requests and 
 *
 * @author Vitaliy_Mukhin
 */
class Input implements Input_I {

	/**
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Throws Exception if data are incompatible
	 *
	 * @param array $data 
	 */
	public function __construct($data) {
		if (!is_array($data)) {
			throw new ErrorException('Source data are incompatible');
		}

		$this->data = $data;
	}

	/**
	 * Get a value from a holded data array.
	 *
	 * @param mixed $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null) {
		return key_exists($key, $this->data) ? $this->data[$key] : $default;
	}

}