<?php

/**
 * Description of Session
 *
 * @author Vitaliy_Mukhin
 */
class Session {
	
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
		
		if(!session_start()) {
			throw new Exception('Cannot start a session');
		}
		
		$sessionId = session_id();
		
		$Session = new Session($sessionId);
		
		self::$i = $Session;
		
		return self::i();
	}
	
	private function __construct($id) {
		$this->id = $id;
		
		$this->data = new Input($_SESSION);
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
	 * @param string $sectionId 
	 * @return Input
	 */
	public function getSection($sectionId) {
		return new Input($this->data->get($sectionId, array()));
	}
	
}