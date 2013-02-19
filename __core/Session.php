<?php

/**
 * Description of Session
 *
 * @author Vitaliy_Mukhin
 */
namespace Core;

use Core\Model\Get;

class Session {

    use Get;

    const USER = 'section_user';

    /**
     *
     * @var Session
     */
    private static $i;

    protected $id;

    protected function __construct(array $data, $sessionId) {
        $this->traitSetData($data);
        $this->id = $sessionId;
    }

    /**
     *
     * @return Session
     * @throws \Exception
     */
    public static function i() {
        if (!empty(self::$i)) {
            return self::$i;
        }

        if (!session_start()) {
            throw new \Exception('Cannot start a session');
        }

        $sessionId = session_id();

        $Session = new Session($_SESSION, $sessionId);

        return self::$i = $Session;
    }

    public function __destruct() {
        $_SESSION = $this->data;
    }

    /**
     *
     * @param string $sectionId
     *
     * @return Input
     */
    public function get($sectionId) {
        $data = $this->traitGetter($sectionId);

        return is_array($data) ? new Input($data) : $data;
    }

    /**
     *
     * @param string $sectionId
     * @param mixed  $params
     *
     * @return Session
     */
    public function set($sectionId, $params = array()) {
        if ($this->traitIsset($sectionId) && is_array($params)) {
            $existing = $this->get($sectionId);
            $params = (is_array($existing)) ? array_merge($existing, $params) : $params;
        }

        $this->data[$sectionId] = $params;

        $this->traitSetData($this->data);

        return $this;
    }
}
