<?php

class Output_Http_Json extends Output_Http {
    
    const RESULT_OK = 'ok';
    
    /**
     * One of self::RESULT_* constants
     *
     * @var string
     */
    protected $result;
    
    public function result($result = null) {
        if (!is_null($result)) {
            $this->result = $result;
        }
        
        return $this->result;
    }
    
}