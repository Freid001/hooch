<?php namespace test\Builder\Sql\Mysql\TableCreate;
use freidcreations\QueryMule\Builder\Sql\Mysql\TableAlter;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnAdd;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnModify;
use freidcreations\QueryMule\Builder\Sql\Common\TableColumnDrop;

/**
 * Class TableAlterTest
 * @package test\Builder\Sql\Common\TableCreate
 */
class TableAlterTest extends \PHPUnit_Framework_TestCase
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

        $this->assertEquals('ALTER TABLE `some_table_name` ADD COLUMN `some_column` VARCHAR(225)',trim($this->table->build()->sql()));
    }

    public function testAlterTableAddBoolean()
    {
        $this->table->alter()->add(function(TableColumnAdd $table){
            $table->add('some_column')->boolean();
        });

        $this->assertEquals('ALTER TABLE `some_table_name` ADD COLUMN `some_column` BOOL',trim($this->table->build()->sql()));
    }

    public function testAlterTableAddDecimal()
    {
        $this->table->alter()->add(function(TableColumnAdd $table){
            $table->add('some_column')->decimal(3,1);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` ADD COLUMN `some_column` DECIMAL(3,1)',trim($this->table->build()->sql()));
    }

    public function testAlterTableAddInt()
    {
        $this->table->alter()->add(function(TableColumnAdd $table){
            $table->add('some_column')->int(11);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` ADD COLUMN `some_column` INT(11)',trim($this->table->build()->sql()));
    }

    public function testAlterTableAddText()
    {
        $this->table->alter()->add(function(TableColumnAdd $table){
            $table->add('some_column')->text();
        });

        $this->assertEquals('ALTER TABLE `some_table_name` ADD COLUMN `some_column` TEXT',trim($this->table->build()->sql()));
    }

    public function testAlterTableAddIncrement()
    {
        $this->table->alter()->add(function(TableColumnAdd $table){
            $table->add('some_column')->increment(11);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` ADD COLUMN `some_column` INT(11) AUTO_INCREMENT',trim($this->table->build()->sql()));
    }

    public function testAlterTableAddPrimaryKey()
    {
        $this->table->alter()->add(function(TableColumnAdd $table){
            $table->primaryKey('some_primary_key',['some_column','another_column']);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` ADD PRIMARY KEY `some_primary_key` (`some_column`,`another_column`)',trim($this->table->build()->sql()));
    }

    public function testAlterTableAddUniqueKey()
    {
        $this->table->alter()->add(function(TableColumnAdd $table){
            $table->uniqueKey('some_primary_key',['some_column','another_column']);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` ADD UNIQUE KEY `some_primary_key` (`some_column`,`another_column`)',trim($this->table->build()->sql()));
    }

    public function testAlterTableAddIndex()
    {
        $this->table->alter()->add(function(TableColumnAdd $table){
            $table->index('some_primary_key',['some_column','another_column']);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` ADD INDEX `some_primary_key` (`some_column`,`another_column`)',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyVarchar()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->modify('some_column')->varchar(225);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` MODIFY COLUMN `some_column` VARCHAR(225)',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyBoolean()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->modify('some_column')->boolean();
        });

        $this->assertEquals('ALTER TABLE `some_table_name` MODIFY COLUMN `some_column` BOOL',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyDecimal()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->modify('some_column')->decimal(4,2);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` MODIFY COLUMN `some_column` DECIMAL(4,2)',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyInt()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->modify('some_column')->int(11);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` MODIFY COLUMN `some_column` INT(11)',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyText()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->modify('some_column')->text();
        });

        $this->assertEquals('ALTER TABLE `some_table_name` MODIFY COLUMN `some_column` TEXT',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyIncrement()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->modify('some_column')->increment(11);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` MODIFY COLUMN `some_column` INT(11) AUTO_INCREMENT',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyRename()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->rename('old_column','new_column')->varchar(225);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` CHANGE `old_column` `new_column` VARCHAR(225)',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyPrimaryKey()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->primaryKey('some_primary_key',['some_column']);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` DROP PRIMARY KEY, ADD PRIMARY KEY `some_primary_key` (`some_column`)',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyUniqueKey()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->uniqueKey('some_unique_key',['some_column']);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` DROP INDEX `some_unique_key`, ADD UNIQUE KEY `some_unique_key` (`some_column`)',trim($this->table->build()->sql()));
    }

    public function testAlterTableModifyIndex()
    {
        $this->table->alter()->modify(function(TableColumnModify $table){
            $table->index('some_index',['some_column']);
        });

        $this->assertEquals('ALTER TABLE `some_table_name` DROP INDEX `some_index`, ADD INDEX `some_index` (`some_column`)',trim($this->table->build()->sql()));
    }

    public function testAlterTableDropColumn()
    {
        $this->table->alter()->drop(function(TableColumnDrop $table){
            $table->drop('some_column');
        });

        $this->assertEquals('ALTER TABLE `some_table_name` DROP COLUMN `some_column`',trim($this->table->build()->sql()));
    }

    public function testAlterTableDropPrimaryKeyWithName()
    {
        $this->table->alter()->drop(function(TableColumnDrop $table){
            $table->primaryKey('some_primary_key');
        });

        $this->assertEquals('ALTER TABLE `some_table_name` DROP PRIMARY KEY `some_primary_key`',trim($this->table->build()->sql()));
    }

    public function testAlterTableDropPrimaryKey()
    {
        $this->table->alter()->drop(function(TableColumnDrop $table){
            $table->primaryKey();
        });

        $this->assertEquals('ALTER TABLE `some_table_name` DROP PRIMARY KEY',trim($this->table->build()->sql()));
    }

    public function testAlterTableDropUniqueKey()
    {
        $this->table->alter()->drop(function(TableColumnDrop $table){
            $table->uniqueKey('some_unique_key');
        });

        $this->assertEquals('ALTER TABLE `some_table_name` DROP INDEX `some_unique_key`',trim($this->table->build()->sql()));
    }

    public function testAlterTableDropIndex()
    {
        $this->table->alter()->drop(function(TableColumnDrop $table){
            $table->index('some_index');
        });

        $this->assertEquals('ALTER TABLE `some_table_name` DROP INDEX `some_index`',trim($this->table->build()->sql()));
    }

    public function testRenameTable()
    {
        $this->table->renameTable('some_new_table_name');
        $this->assertEquals('RENAME TABLE `some_table_name` TO `some_new_table_name`;',trim($this->table->build()->sql()));
    }

    public function testDropTable()
    {
        $this->table->dropTable();
        $this->assertEquals('DROP TABLE `some_table_name`;',trim($this->table->build()->sql()));
    }
}