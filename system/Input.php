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
	 *
	 * @param array $data 
	 */
	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 * Get a value from a holded data array.
	 * Throws Exception if data are incompatible
	 *
	 * @param mixed $key
	 * @param mixed $default
	 * @return mixed
	 * @throws Exception 
	 */
	public function get($key, $default = null) {
		if (is_array($this->data)) {
			return key_exists($key, $this->data) ? $this->data[$key] : $default;
		}

		throw new Exception('Source data are incompatible');
	}

}