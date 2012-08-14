<?php

/**
 * Description of Result
 *
 * @author Виталик
 * 
 * @property-read mixed $value
 * @property-read mixed $error
 */
class Result {

    /**
     *
     * @var mixed
     */
    protected $error;
    protected $value;

    public function __get($name) {
        switch ($name) {
            case 'value':
                return $this->value;
            case 'error':
                return $this->error;
            default:
                throw new Exception('Unknown parameter');
        }
    }

    public function __construct($value, $error = false) {
        $this->value = $value;

        $this->error = $error;
    }

}
