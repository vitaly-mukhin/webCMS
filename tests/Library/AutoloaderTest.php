<?php

/**
 * Test class for Autoloader.
 * Generated by PHPUnit on 2011-11-22 at 00:29:38.
 */
class AutoloaderTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Autoloader
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = Autoloader::i();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		
	}
	
	public function testI() {
		$this->assertTrue(Autoloader::i() instanceof Autoloader);
		$this->assertTrue(Autoloader::i() instanceof Autoloader);
	}
		
	/**
	 * 
	 */
	public function testInvoke() {
		$this->object->pushAutoload('Fw', PATH_LIB . DIRECTORY_SEPARATOR, 'class');
		$this->assertTrue(class_exists('Fw_Config'));
		$this->assertFalse(class_exists('FW_ConfigAbabagalamaga'));
		$this->assertFalse(class_exists('Fw_Config_Ababagalamaga'));
	}

}