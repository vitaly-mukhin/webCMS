<?php

/**
 * Description of Input_Http
 *
 * @author Mukhenok
 */
class Input_Http extends Input {

	const INPUT_ROUTE = 'route_data';
	const INPUT_GET = 'get_data';
	const INPUT_POST = 'post_data';
	const INPUT_SERVER = 'server_data';
	const INPUT_COOKIE = 'coockie_data';

	/**
	 *
	 * @var array
	 */
	private $inputs = array();

	/**
	 *
	 * @var Input_Http
	 */
	private static $defaultInput;

	/**
	 *
	 * @param Input_Http $Input 
	 */
	public static function setDefault(Input_Http $Input) {
		self::$defaultInput = $Input;
	}

	/**
	 *
	 * @return Input_Http
	 */
	public static function getDefault() {
		return self::$defaultInput;
	}

	/**
	 *
	 * @param string $key
	 * @return \Input
	 * @throws Exception 
	 */
	public function get($key) {
		//	first time called this data - should create proxy Input, save it and return it
		if (!key_exists($key, $this->inputs)) {
			$Input = parent::get($key, null);
			if (is_null($Input)) {
				throw new Exception('No such data provided');
			}

			$Input = $Input instanceof Input ? $Input : new Input($Input);

			$this->inputs[$key] = $Input;
		} else {
			$Input = $this->inputs[$key];
		}

		return $this->inputs[$key] = $Input;
	}

}