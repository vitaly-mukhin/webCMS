<?php

/**
 * Description of RequestTest
 *
 * @author Vitaliy_Mukhin
 */
class Request {

    const GET = 'G';
    const POST = 'P';
    const FILES = 'F';
    const COOKIES = 'C';

    /**
     *
     * @var array
     */
    private $data;

    public function __construct() {
        $this->data = array(
                self::GET => $_GET,
                self::POST => $_POST,
                self::FILES => $_FILES,
                self::COOKIES => $_COOKIE,
        );
    }

}