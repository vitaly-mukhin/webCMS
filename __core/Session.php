<?php

/**
 * Description of Session
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;
use Core\Model\Get;

class Session {

	const USER = 'section_user';

	/**
	 *
	 * @var Session
	 */
	private static $instance;

	/**
	 * @var string
	 */
	protected $id;

	protected $data;

	protected function __construct(array $data, $sessionId) {
		$this->data = $data;
		$this->id = $sessionId;
	}

	protected function __clone() {
	}

	/**
	 *
	 * @throws \Exception
	 * @return Session
	 */
	public static function i() {
		if (!empty(static::$instance)) {
			return static::$instance;
		}

		if (!session_start()) {
			throw new \Exception('Cannot start a session');
		}

		$sessionId = session_id();

		return static::$instance = new static($_SESSION, $sessionId);
	}

	public function __destruct() {
		$_SESSION = $this->data;
	}

	/**
	 * @param $sectionId
	 *
	 * @return bool
	 */
	public function exists($sectionId){
		return array_key_exists($sectionId, $this->data);
	}

	/**
	 *
	 * @param string $sectionId
	 *
	 * @return mixed
	 */
	public function get($sectionId) {
		return $this->exists($sectionId) ? $this->data[$sectionId] : null;
	}

	/**
	 *
	 * @param string $sectionId
	 * @param mixed  $params
	 *
	 * @return Session
	 */
	public function set($sectionId, $params = array()) {
		if (($isset = $this->exists($sectionId)) && is_array($params)) {
			$existing = $this->get($sectionId);
			$params   = is_array($existing) ? array_merge($existing, $params) : $params;
		}

		$this->data[$sectionId] = $params;

		return $this;
	}

}
