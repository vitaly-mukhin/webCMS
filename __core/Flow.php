<?php

/**
 * Description of Flow
 *
 * @author Mukhenok
 */
abstract class Flow {
	
	const ACTION_PREFIX = 'action_';
	
	/**
	 *
	 * @var Input
	 */
	protected $Input;
	
	/**
	 *
	 * @var Output 
	 */
	protected $Output;

	/**
	 * Set input/output objects
	 *
	 * @param Input $Input
	 * @param Output $Output 
	 */
	public function init(Input $Input, Output $Output) {
		$this->Input = $Input;
		
		$this->Output = $Output;
	}
	
    /**
     *
     * @param string $action
     * @return boolean|string 
     */
	protected function getAction($action) {
		if (method_exists($this, static::ACTION_PREFIX . $action)) {
			return static::ACTION_PREFIX . $action;
		}
		
		return false;
	}
    
    /**
     *
     * @param string $step
     * @return boolean 
     */
    protected function redirect($step) {
        $action = $this->getAction($step);
        
        if ($action) {
            return $this->$action();
        }
        
        return false;
    }

}