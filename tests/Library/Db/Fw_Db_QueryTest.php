<?php

/**
 * Test class for Fw_Db_Query.
 * Generated by PHPUnit on 2011-11-23 at 01:04:35.
 */
class Fw_Db_QueryTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Fw_Db_Query
	 */
	protected $object;

	/**
	 * 
	 */
	protected function setUp() {
		$this->object = Fw_Db::i()->query();
	}

	/**
	 * 
	 */
	protected function tearDown() {
		
	}

	public function testSelect() {
		$obj = $this->object->select();
		$this->assertTrue($obj == $this->object); //	returns same object link
		$this->assertTrue($this->object->getBehaviour() instanceof Fw_Db_Query_Behaviour_Selectable);
	}

	/**
	 * 
	 */
	public function testExport() {
		$this->assertTrue($this->object->export() === array());
		try {
			//	Unexisting parameter
			$this->object->export('ababagalamaga');
		} catch (Fw_Exception_Db_Query $e) {
			
		}
	}

	/**
	 * @expectedException Fw_Exception_Db_Query_From
	 */
	public function testFromEmpty() {
		$this->object->from('');
	}

	public function testFromSimple() {
		$tableName = 'aaa';
		$obj = $this->object->from($tableName);
		$this->assertTrue($obj == $this->object); //	returns same object link
		$this->assertArrayHasKey(Fw_Db_Query::PARAM_FROM, $this->object->export());
		$this->assertEquals(array($tableName=>array('table'=>$tableName, 'fields'=>'*')), $this->object->export(Fw_Db_Query::PARAM_FROM));
	}

	public function testFromSimpleFields() {
		$tableName = 'aaa';
		$tableFields = 'fields';
		$this->object->from($tableName, $tableFields);
		$this->assertEquals(array($tableName=>array('table'=>$tableName, 'fields'=>$tableFields)), $this->object->export(Fw_Db_Query::PARAM_FROM));
	}

	public function testFromWithAlias() {
		$this->setUp();
		$tableName = array('a'=>'table_name');
		$tableFields = 'fields';
		$obj = $this->object->from($tableName, $tableFields);
		$this->assertEquals(array('a'=>array('table'=>$tableName['a'], 'fields'=>$tableFields)), $this->object->export(Fw_Db_Query::PARAM_FROM));
	}

	public function testFromWithFields() {
		$tableName = 'table_name';
		$tableFields = 'fields';
		$tableName1 = 'table_name1';
		$tableFields1 = 'fields1';
		$this->object->from($tableName, $tableFields);
		$this->object->from($tableName1, $tableFields1);
		$this->assertEquals(array($tableName=>array('table'=>$tableName, 'fields'=>$tableFields), $tableName1=>array('table'=>$tableName1, 'fields'=>$tableFields1)), $this->object->export(Fw_Db_Query::PARAM_FROM));
	}

	public function testWhereAdds() {
		$cond = 'field = ?';
		$value = 'value';
		$this->object->where($cond, $value);
		$this->assertEquals(array(md5($cond)=>new Fw_Db_Query_Where($cond, $value)), $this->object->export(Fw_Db_Query::PARAM_WHERE));
	}
	
	public function testValuesReturn() {
		$obj = $this->object->values(array('a', 'b', 'c'));
		$this->assertTrue($obj == $this->object); //	returns same object link
	}
	
	public function testValuesExport() {
		$obj = $this->object->values(array('a', 'b', 'c'));
		$this->assertArrayHasKey(Fw_Db_Query::PARAM_VALUES, $this->object->export());
	}
	
	public function testValuesValues() {
		$obj = $this->object->values(array('a', 'b', 'c'));
		$this->assertEquals(array(array('a', 'b', 'c')), $this->object->export(Fw_Db_Query::PARAM_VALUES));
	}
	
	public function testValuesMultiValues() {
		$obj = $this->object->values(array('a', 'b', 'c'), array('a1', 'b1', 'c1'));
		$this->assertEquals(array(array('a', 'b', 'c'), array('a1', 'b1', 'c1')), $this->object->export(Fw_Db_Query::PARAM_VALUES));
	}

}