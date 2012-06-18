<?php

/**
 * Description of Fw_Logger_File
 *
 * @author Vitaliy_Mukhin
 */
class Fw_Logger_File extends Fw_Logger_Abstract {
	const TIME_FORMAT = 'Y-m-d H:i:s';

	protected function _prepare($data) {
		$this->_data = array(
				'time' => date(self::TIME_FORMAT),
				'string' => print_r($data, true)
		);
	}

	protected function _write() {
		if (!$this->_Config->file && error_reporting()) {
			throw new Fw_Exception_Logger('Full filename has to be set in Logger config');
		}
		if (!file_exists($this->_Config->file) && error_reporting()) {
			throw new Fw_Exception_Logger('Filename not found');
		}
		if (!($f = fopen($this->_Config->file, 'a'))) {
			return $this;
		}

		fwrite($f, '/' . str_repeat('-', 30) . '/' . PHP_EOL);
		foreach ($this->_data as $k => $v) {
			fwrite($f, sprintf("%s: %s" . PHP_EOL, $k, $v));
		}
		fwrite($f, PHP_EOL);

		fclose($f);

		return $this;
	}

}
