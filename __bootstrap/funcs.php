<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mukhenok
 * Date: 29.06.13
 * Time: 1:29
 */

/**
 * @param array|string $key
 * @param mixed        $default
 * @param array        $source
 *
 * @return mixed
 */
function v($key, $default = null, $source = array()) {
	$result = $default;
	if (is_array($key)) {
		$result = [];
		foreach ($key as $k) {
			$d = is_array($default) ? v($k, null, $default) : $default;
			$result[$k] = (array_key_exists($k, $source)) ? $source[$k] : $d;
		}
	} else {
		$result = (array_key_exists($key, $source)) ? $source[$key] : $default;
	}

	return $result;
}
