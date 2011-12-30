<?php

/**
 * Test class for Fw_Db.
 * Generated by PHPUnit on 2011-11-23 at 00:17:39.
 */
class Fw_DbTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Fw_Db
	 */
	protected $object;

	/**
	 *
	 * @var Fw_Config
	 */
	protected static $configCorrect;

	/**
	 *
	 * @var Fw_Config
	 */
	protected static $configIncorrect;

	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();

		self::$configCorrect = new Fw_Config(array(
					'driver' => 'mysql',
					'server' => 'localhost',
					'port' => '3306',
					'name' => 'fw_testing',
					'user' => 'root',
					'password' => 'root',
					'encoding' => 'utf8'
			));

		self::$configIncorrect = new Fw_Config(array(
					'driver' => 'mysql',
					'server' => 'ababagalamaga',
					'port' => '3306',
					'name' => 'fw_testing',
					'user' => 'root',
					'password' => 'root',
					'encoding' => 'utf8'
			));
	}

	/**
	 * 
	 */
	protected function setUp() {
		$this->object = Fw_Db::i(true);
	}

	/**
	 * @expectedException Fw_Exception_Db_Connection
	 */
	public function testConnectIncorrect() {
		$this->object->connect(self::$configIncorrect);
	}

	public function testQuery() {
		$this->assertTrue($this->object->query() instanceof Fw_Db_Query);
	}

	public function testSetGetLogger() {
		$this->assertTrue($this->object->setLogger(new Fw_Logger_File(new Fw_Config(array()))) instanceof Fw_Db);
		$this->assertTrue($this->object->Logger instanceof Fw_Logger_Db);
	}

}