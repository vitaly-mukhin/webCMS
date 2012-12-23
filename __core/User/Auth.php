<?php

/**
 * Class, which should be included into every entity of User.
 * It contains auth info about user.
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\User;
use Core\Input;
use Core\Output;
use Core\Result;

class Auth {

    const USER_ID         = 'user_id';
    const LOGIN           = 'login';
    const PASSWORD        = 'password';
    const HASH            = 'hash';
    const PASSWORD_REPEAT = 'password_repeat';

    /**
     * Storage, which contains data
     *
     * @var Input
     */
    protected $userAuth = null;

    /**
     * @var \Fw_Db
     */
    protected $Db;

    protected function __construct() {
        $this->Db = \Fw_Db::i();
    }

    /**
     * Factory method for creating new instance, and initiating it
     *
     * @return \Core\User\Auth
     */
    public static function f() {
        $UserAuth = new self();

        $UserAuth->init();

        return $UserAuth;
    }

    /**
     * Check the data for creating a new User
     *
     * @param Input $Data
     *
     * @return \Core\Result
     */
    public function checkReg(Input $Data) {
        $result = true;
        $msgs   = array();

        $login = $Data->get(self::LOGIN);
        $pwd   = $Data->get(self::PASSWORD);
        $pwd2  = $Data->get(self::PASSWORD_REPEAT);

        switch (true) {
            case (!$login):
                $msgs[] = 'Поле Логін не може бути пустим';
                $result = false;
                break;

            case (!preg_match('/^\w[A-z0-9]*/i', $login)):
                $msgs[] = 'Поле Логін повинно починатись із літери та містити як мінімум 2 символи';
                $result = false;
                break;

            case (!$pwd || !$pwd2):
                $msgs[] = 'Поля Пароль та Пароль повторно не можуть бути пустими';
                $result = false;
                break;

            case ($pwd != $pwd2):
                $msgs[] = 'Поля Пароль та Пароль повторно повинні збігатися';
                $result = false;
                break;
        }

        $result = $result && $this->_checkReg($Data);

        return new Result(false, !$result, $msgs);
    }

    /**
     * Initiating the storage with empty data
     */
    protected function init() {

        $Data = $this->getEmptyAuth();

        $this->setAuth($Data);
    }

    /**
     * Returns empty data for initial
     *
     * @return Input
     */
    protected function getEmptyAuth() {
        return new Input(array());
    }

    /**
     * @param Input $Auth
     *
     * @return \Core\User\Auth
     */
    protected function setAuth(Input $Auth) {
        $this->userAuth = $Auth;

        return $this;
    }

    /**
     * Authenticating user with login/password combination
     *
     * @param string $login
     * @param string $password
     *
     * @return \Core\User\Auth
     */
    public function authByPwd($login, $password) {

        $hash = $this->buildHash($login, $password);

        $this->setAuth($this->byHash($login, $hash));

        return $this;
    }

    /**
     * Authenticating user with login/hash combination
     *
     * @param string $login
     * @param string $hash
     *
     * @return \Core\User\Auth
     */
    public function authByHash($login, $hash) {

        $this->setAuth($this->byHash($login, $hash));

        return $this;
    }

    /**
     * Unified method for retrieving the data from storage
     *
     * @param string $field
     *
     * @return mixed
     */
    protected function get($field) {
        return $this->userAuth->get($field);
    }

    /**
     * Providing the registration
     *
     * @param int   $user_id
     * @param Input $Data
     *
     * @return int
     */
    public function reg($user_id, Input $Data) {
        $Data = new Input(array(self::F_USER_ID => $user_id,
                                self::F_LOGIN   => $Data->get(self::LOGIN),
                                self::F_HASH    => $this->buildHash($Data->get(self::LOGIN), $Data->get(self::PASSWORD))));

        return $this->_reg($Data);
    }

    /**
     * Build hash-string with login/password combination
     *
     * @param string $login
     * @param string $password
     *
     * @return string
     */
    private function buildHash($login, $password) {
        return sha1($login . ' / ' . $password);
    }

    /**
     * Get the USER_ID
     *
     * @return int|null
     */
    public function getUserId() {
        return $this->get(self::F_USER_ID);
    }

    /**
     * Get the LOGIN
     *
     * @return string|null
     */
    public function getLogin() {
        return $this->get(self::F_LOGIN);
    }

    /**
     * Get the HASH
     *
     * @return string|null
     */
    public function getHash() {
        return $this->get(self::F_HASH);
    }

    const F_AUTH_ID = 'auth_id';
    const F_USER_ID = 'user_id';
    const F_LOGIN   = 'login';
    const F_HASH    = 'hash';

    /**
     * @var string
     */
    protected $tableName = 'user_auths';

    /**
     * @var array
     */
    private static $fields = array(
        self::F_AUTH_ID, self::F_USER_ID, self::F_LOGIN, self::F_HASH
    );

    /**
     * @param string $login
     * @param string $hash
     *
     * @return Input
     */
    public function byHash($login, $hash) {
        $Q = $this->Db->query();

        $Q->select()->from($this->tableName, self::$fields)->where(self::F_LOGIN . ' = ?', $login)->where(self::F_HASH . ' = ?', $hash);

        $result = $Q->fetchRow();

        return new Input((array) $result);
    }

    protected function _reg(Input $Data) {
        $data = array(
            self::F_USER_ID => $Data->get(self::F_USER_ID),
            self::F_LOGIN   => $Data->get(self::F_LOGIN),
            self::F_HASH    => $Data->get(self::F_HASH)
        );

        $Q = $this->Db->query();

        $Q->insert($this->tableName, $data);

        $id = $Q->fetchRow();

        return $id;
    }

    /**
     * @param Input $Data
     *
     * @return boolean
     */
    protected function _checkReg(Input $Data) {
        $Q = $this->Db->query()->select()->from($this->tableName, self::$fields)->where(self::F_LOGIN . ' = ?', $Data->get(self::LOGIN));

        $result = $Q->fetchRow();

        return empty($result);
    }

}