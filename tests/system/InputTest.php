<?php

/**
 * Test class for Input.
 * Generated by PHPUnit on 2012-04-29 at 23:30:55.
 */
class InputTest extends PHPUnit_Framework_TestCase {

	/**
	 * Input::__construct() accepts array only as source data
	 * 
	 * @covers Input::__construct
	 */
	public function testErrorConstruct() {
		foreach (array(null, 1, 'string', new stdClass()) as $data) {
			try {
				$object = new Input($data);
				$this->assertTrue(false, 'Why does it happen?!');
			} catch (ErrorException $e) {
				$this->assertTrue(true, 'Correct!');
			}
		}
	}

	/**
	 * @covers Input::get
	 */
	public function testGet() {
		$Object = new Input(array('a'=>1, 'b'=>2));

		// test existed values
		$this->assertEquals(1, $Object->get('a'));
		$this->assertEquals(2, $Object->get('b'));

		// test unexisted value
		$this->assertNull($Object->get('c'));
		// test unexisted value with a default set
		$this->assertEquals('default', $Object->get('c', 'default'));
	}

}