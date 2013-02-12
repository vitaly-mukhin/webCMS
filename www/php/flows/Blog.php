<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Mukhenok
 * Date: 08.02.13
 * Time: 0:08
 */

namespace App\Flow;

use App\Flow;

class Blog extends Flow {

	const DATETIME_CREATED_FMT = 'Y-m-d, H:i';

	public function action_default() {
		$q = \Fw_Db::i()->query();
		$q->select()->from(DB_TBL_BLOG)->limit(5)->orderBy(array('datetime_created' => false));
		$list = $q->fetch(function (&$row) {
			$row['datetime_created'] = \DateTime::createFromFormat(DB_DATETIME_FMT, $row['datetime_created'])
					->format(self::DATETIME_CREATED_FMT);
		});

		$this->Output->bind('blogs', $list);
	}

}
