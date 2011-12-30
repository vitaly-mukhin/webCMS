<?php

/**
 * Description of Fw_Logger_File
 *
 * @author Vitaliy_Mukhin
 */
class Fw_Logger_File extends Fw_Logger {
	
	const TIME_FORMAT = 'Y-m-d H:i:s';

	protected function _prepare($data) {
		$this->_data = array(
				'time'=>date(self::TIME_FORMAT),
				'string'=>print_r($data, true)
		);
	}

	protected function _write() {
		return $this;
	}

}
