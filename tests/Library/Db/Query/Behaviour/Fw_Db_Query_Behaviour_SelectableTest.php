<?php

/**
 * Test class for Fw_Db_Query_Behaviour.
 * Generated by PHPUnit on 2011-11-23 at 02:06:43.
 */
class Fw_Db_Query_Behaviour_SelectableTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Fw_Db_Query
	 */
	protected $object;

	/**
	 * 
	 */
	protected function setUp() {
		$this->object = Fw_Db::i()->query()->select();
	}

	/**
	 * 
	 */
	protected function tearDown() {
		
	}

	public function testSqlSimple() {
		$select = $this->object->from('table_name')->getBehaviour();
		$this->assertEquals('SELECT `table_name`.* FROM `table_name`', $select->sql);
	}

//	public function testSqlSimpleJoin() {
//		$select = $this->object->from('table_name')->join()->getBehaviour();
//		$this->assertEquals('SELECT `table_name`.* FROM `table_name`', $select->sql);
//	}

	public function testSqlSimpleWhere() {
		$select = $this->object->from('table_name')->where('field = ?', 'value')->getBehaviour();
		$this->assertEquals('SELECT `table_name`.* FROM `table_name` WHERE (field = ?)', $select->sql);
		$this->assertContains('value', $select->binds);
	}

	public function testSqlSimpleWhereMulti() {
		$select = $this->object->from('table_name')->where('field = ?', 'value')->where('field IS NOT NULL', null)->getBehaviour();
		$this->assertEquals('SELECT `table_name`.* FROM `table_name` WHERE (field = ?) AND (field IS NOT NULL)', $select->sql);
		$this->assertEquals(array('value'), $select->binds);
	}

	public function testSqlSimpleAlias() {
		$select = $this->object->from(array('tb' => 'table_name'))->getBehaviour();
		$this->assertEquals('SELECT tb.* FROM `table_name` tb', $select->sql);
	}

	public function testSqlSingleColumn() {
		$select = $this->object->from('table_name', 'field')->getBehaviour();
		$this->assertEquals('SELECT `table_name`.field FROM `table_name`', $select->sql);
	}

	public function testSqlSingleColumnAlias() {
		$tableName = array('tb' => 'table_name');
		$tableFields = 'field';
		$obj = Fw_Db::i()->query()->from($tableName, $tableFields)->select()->getBehaviour();
		$sql = $obj->sql;
		$this->assertEquals('SELECT tb.field FROM `table_name` tb', $sql);
	}

	public function testSqlMultiColumn() {
		$select = $this->object->from('table_name', array('field1', 'field2'))->getBehaviour();
		$this->assertEquals('SELECT `table_name`.field1, `table_name`.field2 FROM `table_name`', $select->sql);
	}

	public function testSqlMultiColumnAlias() {
		$select = $this->object->from(array('tb' => 'table_name'), array('field1', 'field2'))->getBehaviour();
		$this->assertEquals('SELECT tb.field1, tb.field2 FROM `table_name` tb', $select->sql);
	}

	public function testSqlMultiTables() {
		$select = $this->object->from('table_name', 'field')->from('table_name1', 'field1')->getBehaviour();
		$this->assertEquals('SELECT `table_name`.field, `table_name1`.field1 FROM `table_name`, `table_name1`', $select->sql);
	}

	public function testSqlMultiTablesAlias() {
		$select = $this->object->from(array('tb' => 'table_name'), 'field')->from(array('tb1' => 'table_name1'), 'field1')->getBehaviour();
		$this->assertEquals('SELECT tb.field, tb1.field1 FROM `table_name` tb, `table_name1` tb1', $select->sql);
	}

	/**
	 * @assert 'SELECT `table_name`.field1, `table_name`.field2, `table_name1`.field1 FROM `table_name`, `table_name1`'
	 */
	public function testSqlMultiTablesMultiColumns() {
		$select = $this->object->from('table_name', array('field1', 'field2'))->from('table_name1', 'field1')->getBehaviour();
		$this->assertEquals('SELECT `table_name`.field1, `table_name`.field2, `table_name1`.field1 FROM `table_name`, `table_name1`', $select->sql);
	}

	/**
	 * @assert 'SELECT tb.field1, tb.field2, tb1.field1 FROM `table_name` tb, `table_name1` tb1'
	 */
	public function testSqlMultiTablesMultiColumnsAlias() {
		$select = $this->object->from(array('tb' => 'table_name'), array('field1', 'field2'))->from(array('tb1' => 'table_name1'), 'field1')->getBehaviour();
		$this->assertEquals('SELECT tb.field1, tb.field2, tb1.field1 FROM `table_name` tb, `table_name1` tb1', $select->sql);
	}

	/**
	 * @assert 'SELECT tb.*, tb1.field1 FROM `table_name` tb, `table_name1` tb1'
	 */
	public function testSqlMultiTablesMultiColumnsAlias2() {
		$select = $this->object->from(array('tb' => 'table_name'))->from(array('tb1' => 'table_name1'), 'field1')->getBehaviour();
		$this->assertEquals('SELECT tb.*, tb1.field1 FROM `table_name` tb, `table_name1` tb1', $select->sql);
	}

	/**
	 * @assert 'SELECT tb.*, tb1.* FROM `table_name` tb JOIN `table_name1` tb1 ON (tb.f = tb1.f)'
	 */
	public function testSqlWithJoin() {
		$select = $this->object->from(array('tb' => 'table_name'))->join(array('tb1' => 'table_name1'), 'tb.f = tb1.f')->getBehaviour();
		$this->assertEquals('SELECT tb.*, tb1.* FROM `table_name` tb JOIN `table_name1` tb1 ON (tb.f = tb1.f)', $select->sql);
	}

	/**
	 * @assert 'SELECT tb.field1, tb.field2, tb1.field1 FROM `table_name` tb, `table_name1` tb1'
	 */
	public function testSqlWithJoinMulti() {
		$select = $this->object->from(array('tb' => 'table_name'))
				->join(array('tb1' => 'table_name1'), 'tb.f = tb1.f')
				->join(array('tb2' => 'table_name2'), 'tb.f = tb2.f')
				->getBehaviour();
		$this->assertEquals('SELECT tb.*, tb1.*, tb2.* FROM `table_name` tb JOIN `table_name1` tb1 ON (tb.f = tb1.f), JOIN `table_name2` tb2 ON (tb.f = tb2.f)', $select->sql);
	}
	
	public function testSqlCount() {
		$sql = $this->object->from(TBL_FILM, 'COUNT(film_id) AS cnt')->sql;
		$this->assertEquals('SELECT COUNT(film_id) AS cnt FROM `'.TBL_FILM.'`', $sql);
	}

}