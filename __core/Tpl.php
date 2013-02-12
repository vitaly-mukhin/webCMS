<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mukhenok
 * Date: 09.02.13
 * Time: 16:21
 */

namespace Core;

trait Tpl {

	/**
	 *
	 * @param $string
	 *
	 * @return string
	 */
	protected function getTemplatePath($string = null) {
		switch (true) {
			case ($this instanceof Flow):
				return $this->tplFlow($string);

			case ($this instanceof Block):
				return $this->tplBlock($string);
		}
	}

	/**
	 * @param $action
	 *
	 * @return string
	 */
	private function tplFlow($action) {
		$array = explode('\\', strtolower(get_class($this)));
		// unset standard App and Flow parts
		unset($array[0], $array[1]);
		if (static::IS_ROOT) {
			// unset standard Www
			unset($array[1]);
		}
		$array[] = $action;

		return implode(DIRECTORY_SEPARATOR, $array);
	}

	/**
	 * @param $entity_name
	 *
	 * @return string
	 */
	private function tplBlock($entity_name) {
		$array = explode('\\', strtolower(get_class($this)));
		// unset standard App
		unset($array[0]);

		return implode(DIRECTORY_SEPARATOR, $array);
	}

}
