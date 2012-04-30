<?php

/**
 * Description of Output
 *
 * @author Mukhenok
 */
class Output implements Output_I {

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

}