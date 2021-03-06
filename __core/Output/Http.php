<?php

/**
 * Description of Html
 *
 * @author Mukhenok
 */
class Output_Http extends Output {

	const YEAR_IN_SECONDS = 31536000; // 365*24*60*60
	const HTTP_HEADER = 'http_header';
	const OUTPUT_COOKIE_NAME = 'cookie_name';
	const OUTPUT_COOKIE_VALUE = 'cookie_value';
	const OUTPUT_COOKIE_EXPIRE = 'cookie_exprire';
	const OUTPUT_COOKIE_PATH = 'cookie_path';
	const HTTP_HEADER_SET = true;
	const HTTP_HEADER_UNSET = false;

	/**
	 *
	 * @var Output
	 */
	protected $headers;

	/**
	 *
	 * @var Output
	 */
	protected $cookies;

	/**
	 *
	 * @var string
	 */
	protected $templatePath;

	public function __construct() {
//		parent::__construct();

		$this->headers = new Output;

		$this->cookies = new Output;
	}

	/**
	 *
	 * @param string $value
	 * @param boolean $todo
	 * @return Output
	 */
	public function header($value, $todo = self::HTTP_HEADER_SET) {
		$this->headers->bind($value, $todo);

		return $this;
	}

	/**
	 *
	 * @param string $name
	 * @param string $value
	 * @param int $expire
	 * @param string $path
	 * @return \Output_Http 
	 */
	public function cookie($name, $value, $expire = self::YEAR_IN_SECONDS, $path = '/') {
		$cookie = new Output();
		$cookie->bind(static::OUTPUT_COOKIE_NAME, $name);
		$cookie->bind(static::OUTPUT_COOKIE_VALUE, $value);
		$cookie->bind(static::OUTPUT_COOKIE_EXPIRE, ($expire ? time() + $expire : 0));
		$cookie->bind(static::OUTPUT_COOKIE_PATH, $path);

		$this->cookies->bind($name, $cookie);

		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function headers() {
		$result = array();
		foreach ($this->headers->export() as $header=>$todo) {
			if ($todo == self::HTTP_HEADER_SET) {
				$result[] = $header;
			}
		}
		return $result;
	}

	/**
	 *
	 * @return array
	 */
	public function cookies() {
		return $this->cookies->export();
	}

	/**
	 *
	 * @param string $templatePath
	 * @return \Output_Http 
	 */
	public function setTemplatePath($templatePath) {
		$this->templatePath = $templatePath;

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function getTemplatePath() {
		return $this->templatePath;
	}

}