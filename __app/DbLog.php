<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mukhenok
 * Date: 29.06.13
 * Time: 0:53
 */

namespace App;

class DbLog extends \Fw_Logger_Db {

	static public function i($config, \Fw_Db $Db) {
		$i = new \Fw_Logger_Db(new \Core\Config($config), function ($data) {
			$result = v(['sql', 'binds', 'duration', 'result', 'caller'], null, $data);
			$result['binds'] = json_encode($result['binds']);
			$result['session'] = SESS;

			return $result;
		}, $Db);

		return $i;
	}

}
