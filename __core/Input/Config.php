<?php

/**
 * Description of Input\Config
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\Input;
use Core\Input;

class Config extends Input {

	/**
	 *
	 * @param mixed $key
	 * @param mixed $default
	 *
	 * @return Input\Config|mixed
	 */
	public function get($key, $default = null) {
		$result = parent::get($key, $default);

		if (is_array($result)) {
			return new static($result);
		}

		return $result;
	}

	/**
	 *
	 * @param string $configFile
	 *
	 * @return Input\Config
	 * @throws \ErrorException
	 */
	public static function init($configFile) {
		if (!file_exists($configFile)) {
			throw new \ErrorException(sprintf('Config file not found at <b>%s</b>', $configFile));
		}

		ob_start();

		$data = require $configFile;

		ob_end_clean();

		return new static($data);
	}

}