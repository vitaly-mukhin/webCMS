<?php

/**
 * Description of Session
 *
 * @author Vitaliy_Mukhin
 */
class Session extends ArrayObject {

	const USER = 'section_user';

	/**
	 *
	 * @var Session
	 */
	private static $i;

	/**
	 *
	 * @var string
	 */
	private $id;

	/**
	 *
	 * @var Input
	 */
	private $data;

	/**
	 *
	 * @return Session
	 * @throws Exception 
	 */
	public static function i() {
		if (!empty(self::$i)) {
			return self::$i;
		}

		if (!session_start()) {
			throw new Exception('Cannot start a session');
		}

		$sessionId = session_id();

		$Session = new Session($sessionId);

		self::$i = $Session;

		return self::i();
	}

	public function __construct($id) {
		parent::__construct($_SESSION);

		$this->setId($id);
	}
	
	public function __destruct() {
		// TODO: add saving to $_SESSION on exit;
	}

	/**
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 *
	 * @param string $id
	 * @return \Session 
	 */
	public function setId($id) {
		$this->id = $id;

		return $this;
	}

	/**
	 *
	 * @param string $sectionId 
	 * @return Input
	 */
	public function get($sectionId) {
		$data = $this->offsetExists($sectionId) ? $this->offsetGet($sectionId) : null;
		return is_array($data) ? new Input($data) : $data;
	}

	/**
	 *
	 * @param string $sectionId
	 * @param mixed $params
	 * @return \Session 
	 */
	public function set($sectionId, $params = array()) {

		if ($this->offsetExists($sectionId) && is_array($params)) {
			$existing = $this->offsetGet($sectionId);
			$params = (is_array($existing)) ? array_merge($existing, $params) : $params;
		}

		$this->offsetSet($sectionId, $params);
		return $this;
	}

}