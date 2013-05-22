<?php

namespace App;

use Core\Input as Input;
use Core\Model\Get as Data;
use Core\Result as Result;
use Core\User as User;
use Fw_Db;

/**
 * Class Album
 *
 * @package App
 *
 * @property int    $albumId
 * @property int    $userId
 * @property string $title
 * @property string $dateModified
 * @property string $dateCreated
 * @property string $Author
 *
 */
class Album {

	const WHERE = '__where__';
	use Data;

	const TBL = 'albums';

	const LATEST_N = 10;

	protected static $tblFields = array('album_id', 'title', 'date_created', 'date_modified', 'user_id');

	public function __construct($data) {
		$this->traitSetData($data);
	}

	public function __get($name) {
		if ($name == 'Author') {
			return \Core\User::i($this->userId);
		}

		return $this->traitGetter($name);
	}

	/**
	 *
	 * @param \Core\Input $data
	 *
	 * @return Result
	 */
	public static function add(Input $data) {
		if (($Result = static::validate($data)) && $Result->error) {
			return $Result;
		}

		if ($id = self::_add($Result->value)) {
			return new Result($id);
		}

		return new Result(null, 'Виникла помилка. Спробуйте, будь ласка, пізніше');
	}

	/**
	 *
	 * @param Input $Data
	 *
	 * @return Result
	 */
	protected static function validate(Input $Data) {
		if (!($title = $Data->get('title')) || !trim($title)) {
			return new Result(null, array('Поле "Назва" пусте'));
		}

		return new Result($Data);
	}

	protected static function _add(Input $Data) {
		$values = array(
			'title'   => trim($Data->get('title', '')),
			'user_id' => User::curr()->getUserId(),
		);

		$id = Fw_Db::i()->query()->insert(self::TBL, $values)->fetchRow();

		return $id;
	}

	public static function getLatest($n = self::LATEST_N) {
		return self::getList($n);
	}

	/**
	 *
	 * @param int   $n
	 * @param array $params
	 *
	 * @return array
	 */
	protected static function getList($n = self::LATEST_N, $params = array()) {
		$db = Fw_Db::i();
		$q  = $db->query()->select()->from(self::TBL, self::$tblFields)->limit($n)->orderBy(array('date_created' => false));
		if (!empty($params[self::WHERE])) {
			foreach ($params[self::WHERE] as $where => $value) {
				$q->where($where, $value);
			}
		}
		$list = $q->fetch();

		$result = array();
		foreach ($list as $row) {
			$result[] = new static($row);
		}

		return $result;
	}

	public static function getOwn($n = self::LATEST_N) {
		return self::getList($n, array(self::WHERE => array('user_id = ?' => User::curr()->getUserId())));
	}

	/***********************************************/

	/**
	 *
	 * @param int $id
	 *
	 * @return self|null
	 */
	public static function getById($id) {
		$row = Fw_Db::i()->query()->select()->from(self::TBL, self::$tblFields)->where('album_id = ?', $id)->fetchRow();

		if ($row) {
			return new static($row);
		}

		return null;
	}

	public function __isset($name) {
		if ($name == 'Author') return true;
		return $this->traitIsset($name);
	}

}
