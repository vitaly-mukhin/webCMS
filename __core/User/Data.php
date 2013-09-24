<?php

/**
 * Class, which should be included into every entity of User.
 * It contains all (except auth) info about user.
 *
 * @author Vitaliy_Mukhin
 */
namespace Core\User;
use Core\Input;
use Core\Output;
use Core\Result;

/**
 * Class Data
 *
 * @package Core\User
 *
 * @property int    $user_id
 * @property string $email
 * @property string $username
 */
class Data {

	const USER_ID = 'user_id';

	const PASSWORD = 'password';

	const HASH = 'hash';

	const PASSWORD_REPEAT = 'password_repeat';

	const EMAIL = 'email';

	const USERNAME = 'username';

	const DATE_CREATED = 'date_created';

	//

	const F_USER_ID = 'user_id';

	const F_EMAIL = 'email';

	const F_HASH = 'hash';

	const F_USERNAME = 'username';

	const F_DATE_CREATED = 'date_created';

	const HASH_SALT = ' / ';

	/**
	 * Complete list of fields in users table
	 *
	 * @var array
	 */
	private static $fields
			= array(
				self::F_USER_ID,
				self::F_EMAIL,
				self::F_HASH,
				self::F_DATE_CREATED,
				self::F_USERNAME
			);

	/**
	 * Name of user table
	 *
	 * @var string
	 */
	protected $tableName = DB_TBL_USER;

	/**
	 * @var \Fw_Db
	 */
	protected $Db;

	/**
	 * Storage, which contains data
	 *
	 * @var Input
	 */
	protected $data = null;

	protected function __construct() {
		$this->Db = \Fw_Db::i();

		return $this;
	}

	/**
	 * Factory method for creating a new entity, and initiating it with default data
	 *
	 * @param int|null $userId
	 *
	 * @return self
	 */
	public static function f($userId = null) {
		$UserData = new self();

		$UserData->init($userId);

		return $UserData;
	}

	/**
	 * Init procedure for every entity of this class
	 *
	 * @param $userId
	 *
	 * @return void
	 */
	protected function init($userId) {
		if ($userId && (int)$userId > 0) {
			if (!is_array($userId)) {
				$Q = $this->Db->query();
				$Q->select()->from($this->tableName, self::$fields)->where(self::F_USER_ID . ' = ?', $userId);
				$user_data = $Q->fetchRow() ? : array();
			} else {
				$user_data = $userId;
			}
			$Data = new Input($user_data);
		}
		else {
			$Data = $this->getEmptyData();
		}

		$this->setData($Data);
	}

	/**
	 * Returns empty Input for unlogged user
	 *
	 * @return \Core\Input
	 */
	protected function getEmptyData() {
		return new Input(array());
	}

	/**
	 * Set up the $Data as a source of data about current class entity
	 *
	 * @param Input $Data
	 *
	 * @return self
	 */
	protected function setData(Input $Data) {
		$this->data = $Data;

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get($name) {
		if (isset($this->$name)) return $this->data->get($name);

		throw new \Exception('Unknown property: ' . $name);
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset($name) {
		return in_array($name, [self::F_DATE_CREATED, self::F_EMAIL, self::F_USER_ID, self::F_USERNAME]);
	}

	/**
	 * @param array $data
	 *
	 * @return \Core\Result
	 */
	public function reg(array $data) {
		if (($Result = $this->checkReg($data)) && $Result->error) {
			return $Result;
		}
		$insert = array(
			self::F_EMAIL    => $email = v(self::EMAIL, false, $data),
			self::F_USERNAME => v(self::USERNAME, false, $data),
			self::F_HASH     => static::buildHash($email, v(self::PASSWORD, false, $data)),
		);

		$Q = $this->Db->query()->insert($this->tableName, $insert);

		return new Result($Q->fetchRow());
	}

	/**
	 * Check if we have all required proper values for adding a new user record
	 *
	 * @param array $data
	 *
	 * @return \Core\Result
	 */
	public function checkReg(array $data) {
		$email = v(self::EMAIL, false, $data);
		$pwd = v(self::PASSWORD, false, $data);
		$pwd2 = v(self::PASSWORD_REPEAT, false, $data);

		$messages = array();
		$Q = $this->Db->query()->select()->from($this->tableName, self::F_USER_ID)->where(self::F_EMAIL . ' = ?', $email);
		switch (true) {
			case (!filter_var($email, FILTER_VALIDATE_EMAIL)):
				$messages[self::EMAIL] = 'Поле Email вказано некорректно';
				break;

			case (!$pwd):
				$messages[self::PASSWORD] = 'Поле Пароль не може бути пустим';
				break;
			case (!$pwd2):
				$messages[self::PASSWORD_REPEAT] = 'Поле Пароль повторно не може бути пустим';
				break;

			case ($pwd != $pwd2):
				$messages[self::PASSWORD_REPEAT] = 'Поля Пароль та Пароль повторно повинні збігатися';
				break;

			case ($Q->fetchRow()) :
				$messages[self::EMAIL] = 'Вказаний Email вже зареєстровано';
				break;
		}
		$result = empty($messages);

		return new Result(false, !$result, $messages);
	}

	/**
	 * Build hash-string with login/password combination
	 *
	 * @param string $login
	 * @param string $password
	 *
	 * @return string
	 */
	public static function buildHash($login, $password) {
		return sha1($login . self::HASH_SALT . $password);
	}

	/**
	 * Unified method for retrieving data from source by field name
	 *
	 * @param string $field one of Mapper_User\Data::F_* constants
	 *
	 * @return string
	 */
	protected function get($field) {
		return $this->data->get($field, 'field_not_found');
	}

	/**
	 * Get the DATE_CREATED
	 *
	 * @return string
	 */
	public function getDateCreated() {
		return $this->get(self::F_DATE_CREATED);
	}

	/**
	 * @param string $email
	 * @param string $hash
	 *
	 * @return array|false
	 */
	protected function byHash($email, $hash) {
		$Q = $this->Db->query();

		$Q->select()->from($this->tableName, self::$fields)->where(self::F_EMAIL . ' = ?', $email)->where(self::F_HASH . ' = ?', $hash);

		return $Q->fetchRow();
	}

	/**
	 * Authenticating user with login/password combination
	 *
	 * @param string $email
	 * @param string $password
	 *
	 * @return \Core\User\Auth
	 */
	public function authByPwd($email, $password) {
		$this->init($this->byHash($email, self::buildHash($email, $password)));

		return $this;
	}

	/**
	 * Authenticating user with login/hash combination
	 *
	 * @param string $email
	 * @param string $hash
	 *
	 * @return \Core\User\Auth
	 */
	public function authByHash($email, $hash) {
		$this->init($this->byHash($email, $hash));

		return $this;
	}

}
