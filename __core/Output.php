<?php

/**
 * Description of Output
 *
 * @author Mukhenok
 */
class Output {

    /**
     *
     * @var array
     */
    protected $data = array();

    /**
     *
     * @var mixed
     */
    protected $appender;
    
    /**
     *
     * @var array
     */
    protected $headers = array();

    /**
     *
     * @var Flow
     */
    protected $Flow;
    
    /**
     *
     * @param string $name
     * @param mixed $value
     * @return Output 
     */
    public function bind($name, $value) {
        $this->data[$name] = $value;

        return $this;
    }
    
    /**
     *
     * @param string $value
     * @param boolean $remove
     * @return Output 
     */
    public function header($value, $remove = false) {
        if($remove) {
            if(array_key_exists($value, $this->headers)) {
                unset($this->headers[$value]);
            }
        } else {
            $this->headers[$value] = true;
        }
        
        return $this;
    }
    
    public function headers() {
        return $this->headers;
    }

    /**
     *
     * @param null|mixed $name
     * @return \Output 
     */
    public function appender($name = null) {
        if (is_null($name)) {
            return $this->appender;
        }

        $this->appender = $name;

        return $this;
    }

    /**
     *
     * @param Flow $Flow
     * @return \Output 
     */
    public function flow(Flow $Flow = null) {
        if (is_null($Flow)) {
            return $this->Flow;
        }

        $this->Flow = $Flow;

        return $this;
    }
    
    /**
     *
     * @return array
     */
    public function export() {
        return $this->data;
    }

}