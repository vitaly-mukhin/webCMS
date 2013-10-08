<?php

/**
 * Description of Session
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;
use Core\Model\Get;

class Session implements \ArrayAccess {

	use Arrayable;

	const USER = 'section_user';

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @param array  $data
	 * @param string $sessionId
	 */
	protected function __construct(array $data, $sessionId) {
		$this->data = $data;
		$this->id = $sessionId;
	}

	public function __destruct() {
		$this->toUpdateData();
	}

	protected function __clone() {
	}

	/**
	 *
	 * @throws \Exception
	 * @return Session
	 */
	public static function i() {
		static $i;
		if (!empty($i)) return $i;

		if (!session_start()) {
			throw new \Exception('Cannot start a session');
		}

		$sessionId = session_id();

		return $i = new static($_SESSION, $sessionId);
	}

	/**
	 * Update data into $_SESSION superglobal array
	 */
	protected function toUpdateData() {
		$_SESSION = $this->data;
	}

}
