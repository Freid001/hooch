<?php namespace test\Builder\Sql\Common\TableCreate;
use freidcreations\QueryMule\Builder\Sql\Common\TableAlter;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnAdd;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnModify;

/**
 * Class TableAlterForMysqlTest
 * @package test\Builder\Sql\Common\TableCreate
 */
class TableAlterForMysqlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TableAlter
     */
    private $table;

    function setUp()
    {
        $database = $this->getMockBuilder('freidcreations\QueryMule\Builder\Connection\Database')
            ->disableOriginalConstructor()
            ->getMock();

        $database->expects($this->any())->method('driver')->will($this->returnValue('mysql'));

        $table = $this->getMock('freidcreations\QueryMule\Builder\Sql\Table',[],[
            'some_database_connection_key',
            'some_table_name'
        ]);

        $table->expects($this->any())->method('name')->will($this->returnValue('some_table_name'));
        $table->expects($this->any())->method('dbh')->willReturn($database);

        $this->table = TableAlter::make($table);
    }

    public function tearDown()
    {
        $this->table->reset();
    }

    public function testAlterTable()
    {
        $this->table->alter();
        $this->assertEquals('ALTER TABLE `some_table_name`',trim($this->table->build()->sql()));
    }

    public function testAlterTableAddVarchar()
    {
        $this->table->alter()->add(function(TableColumnAdd $table){
            $table->add('some_column')->varchar(225);
        });

        $this->assertEquals('ALTER TABLE `some_table_name`  ADD COLUMN `some_column` VARCHAR(225)',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyVarchar()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->modify('some_column')->varchar(225);
        });

        $this->assertEquals('ALTER TABLE `some_table_name`  MODIFY COLUMN `some_column` VARCHAR(225)',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyRenameVarchar()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->rename('old_column','new_column')->varchar(225);
        });

        $this->assertEquals('ALTER TABLE `some_table_name`  CHANGE `old_column` `new_column` VARCHAR(225)',trim($this->table->build()->sql()));
    }




}
