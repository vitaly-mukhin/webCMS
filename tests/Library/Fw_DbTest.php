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
		$this->object = Fw_Db::i();
	}

	/**
	 * 
	 */
	protected function tearDown() {
		Fw_Db::i();
	}

	/**
	 * @expectedException Fw_Exception_Db_Connection
	 */
	public function testConnectIncorrect() {
		$this->object->connect(self::$configIncorrect);
	}

//	/**
//	 * 
//	 */
//	public function testConnectCorrect() {
//		$attr = new ReflectionProperty(get_class($this->object), '_connection');
//		$attr->setAccessible(true);
//		$this->assertTrue($attr->getValue($this->object->connect(self::$configCorrect)) instanceof PDO);
//	}

	public function testQuery() {
		$this->assertTrue($this->object->query() instanceof Fw_Db_Query);
	}

	/**
	 * 
	 */
	public function testQueryTable() {
		$tableName = 'testTableName';
		$attr = new ReflectionProperty('Fw_Db_Query', '_table');
		$attr->setAccessible(true);
		$this->assertTrue($attr->getValue($this->object->query($tableName)) == $tableName);
	}

}