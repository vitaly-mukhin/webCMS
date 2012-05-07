<?php

/**
 * Description of Http
 *
 * @author Mukhenok
 */
class Input_Http extends Input {

	const INPUT_ROUTE = 'route_data';
	const INPUT_GET = 'get_data';
	const INPUT_POST = 'post_data';
	const INPUT_COOKIE = 'coockie_data';

	/**
	 *
	 * @var array
	 */
	private static $inputs = array();

	/**
	 *
	 * @param string $key
	 * @return \Input
	 * @throws Exception 
	 */
	public function get($key) {
		//	first time called this data - should create proxy Input, save it and return it
		if (!key_exists($key, self::$inputs)) {
			$Input = parent::get($key, null);
			if (is_null($Input)) {
				throw new Exception('No such data provided');
			}

			$Input = $Input instanceof Input ? $Input : new Input($Input);

			self::$inputs[$key] = $Input;
		}

		return self::$inputs[$key] = $Input;
	}

}