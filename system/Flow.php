<?php

/**
 * Description of Flow
 *
 * @author Mukhenok
 */
abstract class Flow implements Flow_I {
	
	const ACTION_PREFIX = 'action_';
	
	/**
	 *
	 * @var Input_I
	 */
	protected $Input;
	
	/**
	 *
	 * @var Output_I 
	 */
	protected $Output;

	/**
	 * Set input/output objects
	 *
	 * @param Input_I $Input
	 * @param Output_I $Output 
	 */
	public function init(Input_I $Input, Output_I $Output) {
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