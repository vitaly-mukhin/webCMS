<?php

/**
 * Test class for Fw_Logger.
 * Generated by PHPUnit on 2011-12-30 at 14:05:58.
 */
class Fw_Logger_FileTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Fw_Logger_File
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new Fw_Logger_File(new Fw_Config(array()));
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		
	}

	public static function tearDownAfterClass() {
		$dir_handle = opendir(PATH_LOGS);
		while (false !== ($file = readdir($dir_handle))) {
			if ($file != "." && $file != "..") {
				file_put_contents(PATH_LOGS . DIRECTORY_SEPARATOR . $file, '');
			}
		}
	}

	/**
	 * 
	 */
	public function testPrepare() {
		$data = array('aaaa' => 'bbbb');
		@$this->object->save($data);

		$got = $this->object->getData();
		$this->assertTrue(is_array($got));

		//	checking 'time' param
		$this->assertArrayHasKey('time', $got);
		$expected_time = date(Fw_Logger_File::TIME_FORMAT, strtotime($got['time']));
		$this->assertEquals($expected_time, $got['time']);

		//	checking 'string' param
		$this->assertArrayHasKey('string', $got);
		$this->assertEquals(print_r($data, true), $got['string']);
	}

	/**
	 * @expectedException Fw_Exception_Config
	 */
	public function testWriteExceptionNoFileInConfig() {
		$this->object = new Fw_Logger_File(new Fw_Config(array()));
		$data = array('aaaa' => 'bbbb');
		$this->object->save($data);
	}

	/**
	 * @expectedException Fw_Exception_Logger
	 */
	public function testWriteExceptionNoFileForWriting() {
		$this->object = new Fw_Logger_File(new Fw_Config(array('file' => 'bbbbbbb')));
		$data = array('aaaa' => 'bbbb');
		$this->object->save($data);
	}

	/**
	 * 
	 */
	public function testWrite() {
		$log_file = PATH_LOGS . DIRECTORY_SEPARATOR . 'testLoggerFile.log';
		$this->object = new Fw_Logger_File(new Fw_Config(array('file' => $log_file)));
		$data = 'bbbb';

		$file_content_before = file_get_contents($log_file);
		@$this->object->save($data);
		$this->assertGreaterThan(strlen($file_content_before), strlen(file_get_contents($log_file)));
	}

}