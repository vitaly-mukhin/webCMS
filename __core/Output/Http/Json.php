<?php

namespace Core\Output\Http;
use Core\Output\Http;

class Json extends Http {

	const RESULT_OK = 'ok';

	/**
	 * One of self::RESULT_* constants
	 *
	 * @var string
	 */
	protected $result;

	public function result($result = null) {
		if (!is_null($result)) {
			$this->result = $result;
		}

		return $this->result;
	}

}