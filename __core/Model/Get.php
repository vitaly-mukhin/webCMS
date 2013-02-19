<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mukhenok
 * Date: 11.02.13
 * Time: 0:53
 */

namespace Core\Model;
use \Core\Input;

trait Get {

	/**
	 * @var array
	 */
	protected $dataKeys;

	/**
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * @param Input|array $data
	 */
	protected function traitSetData($data) {
		//		$this->data = $data instanceof Input ? $data : new Input($data);
		$this->data = $data;
		if (!empty($data)) {
			$this->setDataKeys();
		}
	}

	private function setDataKeys() {
		$this->dataKeys = array_fill_keys(array_keys($this->data), '');
		array_walk($this->dataKeys,
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
		if (!$this->dataKeys) {
			return false;
		}

		return in_array($name, $this->dataKeys) || array_key_exists($name, $this->dataKeys);
	}

	/**
	 * @param $name
	 *
	 * @return bool
	 */
	protected function traitGetter($name) {
		if (($k = array_search($name, $this->dataKeys)) !== false) {
			return $this->data[$k];
		}
		if (array_key_exists($name, $this->dataKeys)) {
			return $this->data[$name];
		}
	}
}
