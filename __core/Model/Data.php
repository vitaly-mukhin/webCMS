<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mukhenok
 * Date: 11.02.13
 * Time: 0:53
 */

namespace Core\Model;
use \Core\Input;

trait Data {

	/**
	 * @var array
	 */
	protected static $dataKeys;

	/**
	 *
	 * @var Input
	 */
	protected $data;

	/**
	 * @param Input|array $data
	 */
	protected function traitSetData($data) {
		$this->data = $data instanceof Input ? $data : new Input($data);
		if (!static::$dataKeys) {
			$this->setDataKeys();
		}
	}

	private function setDataKeys() {
		self::$dataKeys = array_fill_keys($this->data->keys(), '');
		array_walk(self::$dataKeys,
			function (&$v, $k) {
				$n = str_replace('_', ' ', $k);
				$v = lcfirst(str_replace(' ', '', ucwords($n)));
			});
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	protected function traitIsset($name) {
		return in_array($name, self::$dataKeys);
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	protected function traitGetter($name) {
		if (($k = array_search($name, self::$dataKeys)) !== false) {
			return $this->data->get($k);
		}
	}
}
