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
use Core\Model\Data;

/**
 * Class Auth
 *
 * @package Core\User
 *
 * @property int    $authId
 * @property int    $userId
 * @property string $login
 * @property string $hash
 */
class Auth {

	use Data;

	const USER_ID = 'user_id';

	const LOGIN = 'login';

	const PASSWORD = 'password';

	const HASH = 'hash';

	const PASSWORD_REPEAT = 'password_repeat';

	const F_AUTH_ID = 'auth_id';

	const F_USER_ID = 'user_id';

	const F_LOGIN = 'login';

	const F_HASH = 'hash';

	/**
	 * @var array
	 */
	private static $fields
			= array(
				self::F_AUTH_ID,
				self::F_USER_ID,
				self::F_LOGIN,
				self::F_HASH
			);

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

	/**
	 * @var string
	 */
	protected $tableName = 'user_auths';

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
	 * Initiating the storage with empty data
	 */
	protected function init() {
		$this->traitSetData(self::getEmptyAuth());

		//		$this->setAuth();
	}

	/**
	 * Returns empty data for initial
	 *
	 * @return Input
	 */
	protected static function getEmptyAuth() {
		return new Input(array_fill_keys(self::$fields, false));
	}

	public function __get($name) {
		return $this->traitGetter($name);
	}

	public function __isset($name) {
		return $this->traitIsset($name);
	}

	/**
	 * Check the data for creating a new User
	 *
	 * @param Input $Data
	 *
	 * @return \Core\Result
	 */
	public function checkReg(Input $Data) {
		$result   = true;
		$messages = array();

		$login = $Data->get(self::LOGIN);
		$pwd   = $Data->get(self::PASSWORD);
		$pwd2  = $Data->get(self::PASSWORD_REPEAT);

		switch (true) {
			case (!$login):
				$messages[] = 'Поле Логін не може бути пустим';
				break;

			case (!preg_match('/^\w[A-z0-9]*/i', $login)):
				$messages[] = 'Поле Логін повинно починатись із літери та містити як мінімум 2 символи';
				break;

			case (!$pwd || !$pwd2):
				$messages[] = 'Поля Пароль та Пароль повторно не можуть бути пустими';
				break;

			case ($pwd != $pwd2):
				$messages[] = 'Поля Пароль та Пароль повторно повинні збігатися';
				break;
		}
		$result = empty($messages);

		$result = $result && $this->_checkReg($Data);

		return new Result(false, !$result, $messages);
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

	/**
	 * Authenticating user with login/password combination
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return \Core\User\Auth
	 */
	public function authByPwd($login, $password) {
		$hash = self::buildHash($login, $password);

		$data = $this->byHash($login, $hash);
		if ($data) {
			$this->traitSetData($data);
		}

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
		$data = $this->byHash($login, $hash);
		if ($data) {
			$this->traitSetData($data);
		}

		return $this;
	}

	/**
	 * @param string $login
	 * @param string $hash
	 *
	 * @return Input
	 */
	public function byHash($login, $hash) {
		$Q = $this->Db->query();

		$Q->select()->from($this->tableName, self::$fields)->where(self::F_LOGIN . ' = ?', $login)->where(self::F_HASH . ' = ?', $hash);

		return $Q->fetchRow();
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
		$Data = new Input(array(
		                       self::F_USER_ID => $user_id,
		                       self::F_LOGIN   => $Data->get(self::LOGIN),
		                       self::F_HASH    => $this->buildHash($Data->get(self::LOGIN), $Data->get(self::PASSWORD))
		                  ));

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
	private static function buildHash($login, $password) {
		return sha1($login . ' / ' . $password);
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
}
