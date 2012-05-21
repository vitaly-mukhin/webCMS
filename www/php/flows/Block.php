<?php

/**
 * Description of Flow_Block
 *
 * @author Vitaliy_Mukhin
 * 
 * @todo Something's wrong - we cannot load a single block by http://website/block/login, probably
 * we should use correct structure:
 * Flow_Www -> Flow_Www_Index...
 */
class Flow_Block extends Flow {

	public function action_default() {
		$this->runChildFlow($this->Input->get(Input_Http::INPUT_ROUTE)->get('action'));
	}

}