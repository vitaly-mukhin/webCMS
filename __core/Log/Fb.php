<?php

/**
 * Description of Fb
 *
 * @author Mukhenok
 */
class Log_Fb extends Fw_Logger_Abstract {

	protected function prepareForWrite($data) {
		$this->_data = json_encode($data);
	}

	protected function write() {
		$str = '';
		
//		foreach ($this->_data as $k => $v) {
//			$str .= sprintf("%s: %s" . PHP_EOL, $k, $v);
//		}
		$str = $this->_data;
		
		Fb::log($str, 'sql');

		return $this;
	}

}