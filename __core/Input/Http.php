<?php

/**
 * Description of Input\Http
 *
 * @author Mukhenok
 */
namespace Core\Input;
use Core\Input;

class Http extends Input {

	const INPUT_ROUTE  = 'route_data';
	const INPUT_GET    = 'get_data';
	const INPUT_POST   = 'post_data';
	const INPUT_SERVER = 'server_data';
	const INPUT_COOKIE = 'coockie_data';

	/**
	 *
	 * @var array
	 */
	private $inputs = array();

	/**
	 *
	 * @var Input\Http
	 */
	private static $defaultInput;

	/**
	 *
	 * @param Input\Http $Input
	 */
	public static function setDefault(Input\Http $Input) {
		self::$defaultInput = $Input;
	}

	/**
	 *
	 * @return Input\Http
	 */
	public static function getDefault() {
		return self::$defaultInput;
	}

	/**
	 *
	 * @param string $key
	 * @param null   $default
	 *
	 * @throws \Exception
	 * @return Input
	 */
	public function get($key, $default = null) {
		//	first time called this data - should create proxy Input, save it and return it
		if (!key_exists($key, $this->inputs)) {
			$Input = parent::get($key, null);
			if (is_null($Input)) {
				throw new \Exception('No such data provided');
			}

			$Input = $Input instanceof Input ? $Input : new Input($Input);

			$this->inputs[$key] = $Input;
		} else {
			$Input = $this->inputs[$key];
		}

		return $this->inputs[$key] = $Input;
	}

}